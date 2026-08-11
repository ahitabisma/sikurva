<?php

namespace App\Http\Controllers;

use App\Http\Services\PointService;
use Helper\CacheHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    public function index()
    {
        // Caching data banners
        $banners = Cache::remember('lp_banners', now()->addMinutes(30), function () {
            return DB::table('lp_banners')->get();
        });

        // Caching data profile
        $profile = Cache::remember('lp_profile', now()->addMinutes(30), function () {
            return DB::table('lp_profiles')->first();
        });

        // Caching data layanan
        $layanans = Cache::remember('lp_layanans', now()->addMinutes(30), function () {
            return DB::table('lp_layanans')->get();
        });

        // Caching data testimoni
        $testimonis = Cache::remember('lp_testimonis', now()->addMinutes(30), function () {
            return DB::table('testimonis')
                ->join('users', 'testimonis.user_id', '=', 'users.id')
                ->leftJoin('instansis', 'users.instansi_id', '=', 'instansis.id')
                ->select(
                    'testimonis.rating',
                    'testimonis.testimoni',
                    'users.name as user_name',
                    'instansis.name as instansi_name'
                )
                ->limit(5)
                ->orderBy('testimonis.updated_at', 'asc')
                ->get();
        });

        // Caching data paket
        $pakets = Cache::remember('lp_pakets', now()->addMinutes(30), function () {
            return DB::table('subscriptions')
                ->limit(5)
                ->orderBy('subscriptions.created_at', 'desc')
                ->get();
        });

        // Caching Menu Helps
        $helps = Cache::remember('lp_helps', now()->addDays(7), function () {
            return DB::table('lp_helps')->get();
        });

        // Point Setting
        $pointSettingPenggunaBaruNakes = Cache::remember('point_setting_pengguna_baru_nakes', now()->addDays(7), function () {
            return $this->pointService->findSettingByNameAndUserType('PENGGUNA-BARU', 'nakes');
        });
        $pointSettingPenggunaBaruAwam = Cache::remember('point_setting_pengguna_baru_awam', now()->addDays(7), function () {
            return $this->pointService->findSettingByNameAndUserType('PENGGUNA-BARU', 'non-nakes');
        });

        // Contact
        $contact = Cache::remember('lp_contact', now()->addDays(7), function () {
            return DB::table('lp_settings')->whereIn('key', ['email', 'no_wa'])->get()->pluck('value', 'key');
        });

        if (isset($contact['no_wa']) && $contact['no_wa'] != null) {
            $contact['no_wa_convert'] = preg_replace('/^0/', '62', $contact['no_wa']) ?? null;
        }

        return view('home')->with([
            'banners' => $banners,
            'layanans' => $layanans,
            'profile' => $profile,
            'testimonis' => $testimonis,
            'pakets' => $pakets,
            'contact' => $contact,
            'pointSettingPenggunaBaruNakes' => $pointSettingPenggunaBaruNakes,
            'pointSettingPenggunaBaruAwam' => $pointSettingPenggunaBaruAwam,
            'helps' => $helps,
        ]);
    }

    /**
     * Function to clear cache when data changes
     */
    public function clearCache()
    {
        Cache::forget('lp_banners');
        Cache::forget('lp_layanans');
        Cache::forget('lp_profile');
        Cache::forget('lp_testimonis');
        Cache::forget('lp_pakets');
        Cache::forget('lp_helps');
    }
}
