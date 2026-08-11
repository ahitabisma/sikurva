<?php

namespace App\Http\Controllers;

use App\Http\Services\PointService;
use App\Models\LpSetting;
use Carbon\Carbon;
use Helper\CacheHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $pointService;
    protected $superAdmin;

    public function __construct(PointService $pointService)
    {
        // Use dependency injection to get the PointService instance
        $this->pointService = $pointService;
        $this->superAdmin = CacheHelper::getSuperAdmin();
    }

    public function superAdminDashoard(Request $request)
    {
        // Gunakan Carbon untuk tanggal (lebih aman dan fleksibel)
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(7)));
        $endDate = Carbon::parse($request->input('end_date', now()));

        // Hitung statistik ringkasan dalam satu query gabungan (lebih hemat roundtrip DB)
        $summary = DB::table('users')
            ->selectRaw('
                (SELECT COUNT(*) FROM instansis) as klinik,
                SUM(CASE WHEN is_nakes = true THEN 1 ELSE 0 END) as nakes,
                SUM(CASE WHEN is_nakes = false AND id != ? THEN 1 ELSE 0 END) as non_nakes,
                (SELECT COUNT(*) FROM patients) as patients
            ', [$this->superAdmin->id])
            ->first();

        $count = [
            'klinik' => $summary->klinik,
            'nakes' => $summary->nakes,
            'nonNakes' => $summary->non_nakes,
            'patients' => $summary->patients,
        ];

        // Ambil aktivitas terakhir
        $lastActivities = DB::table('point_transactions')
            ->leftJoin('users', 'point_transactions.user_id', '=', 'users.id')
            ->leftJoin('instansis', 'point_transactions.instansi_id', '=', 'instansis.id')
            ->leftJoin('patients', 'point_transactions.patient_id', '=', 'patients.id')
            ->select(
                'users.name as user_name',
                'instansis.name as instansi_name',
                DB::raw('SUM(point_transactions.points) as points'),
                'point_transactions.type',
                'point_transactions.description',
                'point_transactions.created_at',
                'patients.nama as patient_name'
            )
            ->whereBetween('point_transactions.created_at', [
                $startDate->startOfDay(),
                $endDate->endOfDay()
            ])
            ->groupBy(
                'point_transactions.description',
                'point_transactions.created_at',
                'point_transactions.type',
                'users.name',
                'instansis.name',
                'patients.nama'
            )
            ->orderByDesc('point_transactions.created_at')
            ->paginate(25)
            ->appends([
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString()
            ]);

        $contact = Cache::remember('lp_contact', now()->addDays(7), function () {
            return LpSetting::whereIn('key', ['email', 'no_wa'])->get()->pluck('value', 'key');
        });

        return view('super-admin.dashboard')->with([
            'title' => 'Dashboard',
            'count' => $count,
            'contact' => $contact,
            'lastActivities' => $lastActivities,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }

    public function updateContact(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|string|max:255',
                'no_wa' => 'required|string|max:15',
            ]);

            LpSetting::updateOrCreate(
                ['key' => 'email'],
                ['value' => $validated['email']]
            );

            LpSetting::updateOrCreate(
                ['key' => 'no_wa'],
                ['value' => $validated['no_wa']]
            );

            Cache::forget('lp_contact');

            return redirect()->back()->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            //throw $th;
            Log::error('Error updating contact: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data');
        }
    }

    public function adminDashboard(Request $request)
    {
        // Gunakan Carbon untuk tanggal (lebih aman dan fleksibel)
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(7)));
        $endDate = Carbon::parse($request->input('end_date', now()));

        $user = Auth::user();

        $paramsActivity = Auth::user()->instansi ? ['point_transactions.instansi_id' => $user->instansi->id] : ['point_transactions.user_id' => $user->id];

        // Ambil point settings dari cache
        $pointSettingDownload = Cache::remember('point_setting_download', now()->addDays(7), function () {
            return $this->pointService->findSettingByName('DOWNLOAD-GRAFIK');
        });
        $pointSettingPenilaian = Cache::remember('point_setting_penilaian', now()->addDays(7), function () {
            return  $this->pointService->findSettingByName('PENILAIAN');
        });

        // Query aktivitas terakhir (paginate)
        $lastActivities = DB::table('point_transactions')
            ->where($paramsActivity)
            ->leftJoin('point_settings', 'point_transactions.point_setting_id', '=', 'point_settings.id')
            ->leftJoin('patients', 'point_transactions.patient_id', '=', 'patients.id')
            ->select(
                DB::raw('SUM(point_transactions.points) as points'),
                'point_transactions.type',
                'point_transactions.description',
                'point_transactions.created_at',
                'point_settings.name as point_setting_name',
                'patients.nama as patient_name',
                'point_transactions.point_setting_id'
            )
            ->whereBetween('point_transactions.created_at', [
                $startDate->startOfDay(),
                $endDate->endOfDay()
            ])
            ->groupBy(
                'point_transactions.description',
                'point_transactions.created_at',
                'point_transactions.point_setting_id',
                'point_transactions.type',
                'patients.nama',
                'point_settings.name'
            )
            ->orderByDesc('point_transactions.created_at')
            ->paginate(25)
            ->appends([
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString()
            ]);

        // Metrics
        // Total Patient
        $patients = DB::table('patients')->where('created_by', $user->id)->count();
        // Hitung total transaksi berdasarkan point_setting
        $total = DB::table('point_transactions')
            ->where($paramsActivity)
            ->selectRaw('
                    COUNT(CASE WHEN point_setting_id = ? THEN 1 END) as total_generate_pdf,
                    COUNT(CASE WHEN point_setting_id = ? THEN 1 END) as total_generate_penilaian
                ', [
                $pointSettingDownload->id,
                $pointSettingPenilaian->id
            ])
            ->first();

        $metrics = [
            'patients' => $patients,
            'total_generate_pdf' => $total->total_generate_pdf ?? 0,
            'total_generate_penilaian' => $total->total_generate_penilaian ?? 0,
        ];

        return view('admin.aktivitas.index')->with([
            'title' => 'Aktivitas Terakhir',
            'patients' => $patients,
            'lastActivities' => $lastActivities,
            'metrics' => $metrics,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }
}
