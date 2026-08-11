<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.tailwind');

        // Cache Point Setting agar tidak melakukan query berulang kali
        View::composer('*', function ($view) {
            $pointSettings = Cache::rememberForever('point_setting', function () {
                return DB::table('point_settings')->get();
            });

            $view->with('pointSettings', $pointSettings);
        });

        View::composer(['layouts.partials.header', 'layouts.partials.header-super-admin', 'profile.show'], function ($view) {
            $user = Auth::user();

            $cachePointKey = $user->instansi_id ? 'total_poin_instansi_' . $user->instansi_id : 'total_poin_user_' . $user->id;

            [$totalPoin, $expiredAt] = Cache::remember($cachePointKey, now()->addHour(), function () use ($user) {
                $query = DB::table('point_batches')
                    ->where($user->instansi_id ? 'instansi_id' : 'user_id', $user->instansi_id ?? $user->id)
                    ->where('expired_at', '>=', now());

                $totalPoin = $query->sum('remaining_points');
                $expiredAt = $query->max('expired_at');

                return [$totalPoin, $expiredAt];
            });

            // Cache header berdasarkan user ID
            $cacheUserHeaderKey = 'header_user_' . $user->id;
            $header = Cache::remember($cacheUserHeaderKey, now()->addHour(), function () use ($user) {
                return $user->is_nakes
                    ? ($user->instansi->header ?? null)
                    : ($user->header ?? null);
            });

            $view->with([
                'header' => $header,
                'totalPoin' => $totalPoin,
                'expiredAt' => $expiredAt,
            ]);
        });

        View::composer(['layouts.partials.sidebar', 'layouts.partials.sidebar-super-admin', 'home', 'layouts.tailadmin', 'layouts.guest'], function ($view) {
            // Cache logo super admin (umum)
            $cacheSuperAdminLogoKey = 'header_super_admin';
            $logo = Cache::remember($cacheSuperAdminLogoKey, now()->addHour(), function () {
                return User::role('super-admin')->value('header') ?? null;
            });

            $view->with([
                'logo' => $logo,
            ]);
        });
    }
}
