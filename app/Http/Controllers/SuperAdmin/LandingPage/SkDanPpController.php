<?php

namespace App\Http\Controllers\SuperAdmin\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LpSetting;
use Illuminate\Support\Facades\Storage;

class SkDanPpController extends Controller
{
    private $skKey = 'syarat_ketentuan';
    private $ppKey = 'privacy_policy';
    private $uploadPath = 'sk-pp';

    public function index()
    {
        // Get syarat ketentuan settings
        $skSetting = LpSetting::where('key', $this->skKey)->first();
        $sk = [];
        if ($skSetting) {
            $sk = ['file_path' => $skSetting->value];
            // Explicitly use Storage::url to ensure proper URL generation
            $sk['file'] = Storage::url($sk['file_path']);
            $sk['file_name'] = basename($sk['file_path']);
        }

        // Get privacy policy settings
        $ppSetting = LpSetting::where('key', $this->ppKey)->first();
        $pp = [];
        if ($ppSetting) {
            $pp = ['file_path' => $ppSetting->value];
            // Explicitly use Storage::url to ensure proper URL generation
            $pp['file'] = Storage::url($pp['file_path']);
            $pp['file_name'] = basename($pp['file_path']);
        }

        return view('super-admin.landing-page.sk-dan-pp.index', compact('sk', 'pp'));
    }

    /**
     * Update syarat ketentuan settings
     */
    public function updateSk(Request $request)
    {
        $request->validate([
            'sk_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        // Get existing settings
        $skSetting = LpSetting::where('key', $this->skKey)->first();

        // Handle file upload
        if ($request->hasFile('sk_file')) {
            $file = $request->file('sk_file');
            $fileName = 'syarat_ketentuan_' . time() . '.pdf';

            // Delete old file if exists
            if ($skSetting && Storage::disk('public')->exists($skSetting->value)) {
                Storage::disk('public')->delete($skSetting->value);
            }

            // Store the file in storage/app/public/sk-pp directory
            $filePath = $file->storeAs($this->uploadPath, $fileName, 'public');

            // Update or create setting with just the path
            LpSetting::updateOrCreate(
                ['key' => $this->skKey],
                ['value' => $filePath]
            );
        }

        return redirect()->route('super-admin.landing-page.sk-pp.index')
            ->with('success', 'Syarat dan Ketentuan berhasil diperbarui!');
    }

    /**
     * Update privacy policy settings
     */
    public function updatePp(Request $request)
    {
        $request->validate([
            'pp_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        // Get existing settings
        $ppSetting = LpSetting::where('key', $this->ppKey)->first();

        // Handle file upload
        if ($request->hasFile('pp_file')) {
            $file = $request->file('pp_file');
            $fileName = 'privacy_policy_' . time() . '.pdf';

            // Delete old file if exists
            if ($ppSetting && Storage::disk('public')->exists($ppSetting->value)) {
                Storage::disk('public')->delete($ppSetting->value);
            }

            // Store the file in storage/app/public/sk-pp directory
            $filePath = $file->storeAs($this->uploadPath, $fileName, 'public');

            // Update or create setting with just the path
            LpSetting::updateOrCreate(
                ['key' => $this->ppKey],
                ['value' => $filePath]
            );
        }

        return redirect()->route('super-admin.landing-page.sk-pp.index')
            ->with('success', 'Privacy Policy berhasil diperbarui!');
    }
}
