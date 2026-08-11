<?php

namespace App\Http\Controllers\SuperAdmin\Langganan;

use App\Http\Controllers\Controller;
use App\Models\PointSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    private $pointSettingNeedDuration = [
        'REFERRER',
        'REFERRAL',
        'PENGGUNA-BARU',
    ];

    public function index()
    {
        $allSettings = DB::table('point_settings')
            ->select('id', 'type', 'user_type', 'name', 'points', 'duration', 'duration_type', 'created_at', 'updated_at')
            ->orderBy('id', 'desc')
            ->orderBy('name')
            ->orderBy('user_type') // nakes dulu, lalu non-nakes
            ->get()
            ->groupBy('name');

        return view('super-admin.langganan.setting.index')->with([
            'title' => 'Setting Point',
            'settings' => $allSettings,
        ]);
    }

    public function edit($name)
    {
        $settings = DB::table('point_settings')
            ->where('name', $name)
            ->get();

        $isSingle = $settings->count() === 1 && is_null($settings->first()->user_type);

        // Check if this is a setting that needs duration fields
        $showDuration = in_array(strtoupper($name), $this->pointSettingNeedDuration);

        return view('super-admin.langganan.setting.edit', [
            'title' => 'Edit Setting Point',
            'setting' => $isSingle ? $settings->first() : $settings,
            'showDuration' => $showDuration,
            'pointSettingNeedDuration' => $this->pointSettingNeedDuration,
        ]);
    }

    public function update(Request $request, $name)
    {
        // Validate request
        $rules = [];

        // Is multiple settings?
        if ($request->has('point') && is_array($request->point)) {
            foreach ($request->point as $key => $value) {
                $rules["point.$key"] = 'required|numeric|min:0';

                // If this setting needs duration
                if (in_array(strtoupper($name), $this->pointSettingNeedDuration)) {
                    $rules["duration.$key"] = 'required|numeric|min:1';
                    $rules["duration_type.$key"] = 'required|in:hari,bulan,tahun';
                }
            }
        } else {
            $rules['point'] = 'required|numeric|min:0';

            // If this setting needs duration
            if (in_array(strtoupper($name), $this->pointSettingNeedDuration)) {
                $rules['duration'] = 'required|numeric|min:1';
                $rules['duration_type'] = 'required|in:hari,bulan,tahun';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        // Check if this setting needs duration fields
        $needsDuration = in_array(strtoupper($name), $this->pointSettingNeedDuration);

        // Cek apakah multiple user_type (array) atau single
        if ($request->has('point') && is_array($request->point)) {
            foreach ($request->point as $userType => $point) {
                $updateData = [
                    'points' => $point,
                    'user_type' => $request->user_type[$userType],
                    'updated_at' => now(),
                ];

                // Add duration fields if needed
                if ($needsDuration) {
                    $updateData['duration'] = $request->duration[$userType] ?? null;
                    $updateData['duration_type'] = $request->duration_type[$userType] ?? null;
                }

                DB::table('point_settings')
                    ->where('id', $request->id[$userType])
                    ->update($updateData);
                }
            } else {
            // Hanya satu setting (umum / user_type null)
            $updateData = [
                'points' => $request->point,
                'user_type' => $request->user_type,
                'updated_at' => now(),
            ];

            // Add duration fields if needed
            if ($needsDuration) {
                $updateData['duration'] = $request->duration ?? null;
                $updateData['duration_type'] = $request->duration_type ?? null;
            }

            DB::table('point_settings')
            ->where('id', $request->id)
            ->update($updateData);
        }

        Cache::forget('point_setting');
        Cache::forget('point_setting_pengguna_baru_nakes');
        Cache::forget('point_setting_pengguna_baru_awam');

        return redirect()
        ->route('super-admin.langganan.setting.index')
        ->with('success', 'Setting point berhasil diperbarui.');
    }
}
