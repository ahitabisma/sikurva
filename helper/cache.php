<?php

namespace Helper;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheHelper
{
    /**
     * Get super admin user from cache
     *
     * @return object
     */
    public static function getSuperAdmin()
    {
        $cacheKey = 'super_admin';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        // If not cached, fetch from database and cache it
        return Cache::rememberForever('super_admin', function () {
            return DB::table('users')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('roles.name', 'super-admin')
                ->select('users.*')
                ->first();
        });
    }

    /**
     * Get kurva data from cache based on gender
     *
     * @param string $table Table name
     * @param string $column Column to order by
     * @param string $jenisKelamin Gender (L or P)
     * @return object
     */
    public static function getKurvaData($table, $column, $jenisKelamin)
    {
        $cacheKey = "kurva_data_{$table}_{$jenisKelamin}";

        if (!Cache::has($cacheKey)) {
            $tableData = DB::table($table)
                ->where('jenis_kelamin', $jenisKelamin)
                ->orderBy($column, 'asc')
                ->get();

            Cache::put($cacheKey, $tableData, now()->addMonths(6));
        }

        return Cache::get($cacheKey);
    }

    /**
     * Get kurva table settings from cache
     *
     * @return object
     */
    public static function getKurvaTableSettings()
    {
        if (Cache::has('kurva_table_settings')) {
            return Cache::get('kurva_table_settings');
        }
        // If not cached, fetch from database and cache it
        return Cache::remember('kurva_table_settings', now()->addDays(7), function () {
            return DB::table('kurva_table_settings')->get();
        });
    }
}
