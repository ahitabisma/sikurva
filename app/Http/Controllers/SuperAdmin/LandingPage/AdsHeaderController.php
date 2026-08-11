<?php

namespace App\Http\Controllers\SuperAdmin\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LpSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdsHeaderController extends Controller
{
    private $headerKey = 'header';
    private $adsKey = 'ads';
    private $uploadPath = 'img-public/lp-setting';

    public function index()
    {
        $headerWebsite = (Auth::user()->is_nakes ? Auth::user()->instansi->header : Auth::user()->header) ?? null;
        // Get header settings
        $headerSetting = DB::table('lp_settings')->where('key', $this->headerKey)->first();
        $header = [];
        if ($headerSetting) {
            // For header, we need to prepend the path since we store only the filename
            $header = ['image_path' => "img-public/header/{$headerSetting->value}"];
            $header['image'] = asset($header['image_path']);
        }

        // Get ads settings
        $adsSetting = DB::table('lp_settings')->where('key', $this->adsKey)->first();
        $ads = [];
        if ($adsSetting) {
            $ads = ['image_path' => $adsSetting->value];
            $ads['image'] = asset($ads['image_path']);
        }

        return view('super-admin.landing-page.ads-header.index', compact('header', 'ads', 'headerWebsite'));
    }

    /**
     * Update header settings
     */
    public function updateHeader(Request $request)
    {
        $request->validate([
            'header_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:100',
        ]);

        // Get existing settings
        $headerSetting = DB::table('lp_settings')->where('key', $this->headerKey)->first();

        // Create upload directory if it doesn't exist
        $uploadDir = public_path('img-public/header');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        // Handle image upload
        if ($request->hasFile('header_image')) {
            $file = $request->file('header_image');
            $fileName = 'header_' . time() . '.' . $file->getClientOriginalExtension();

            // Delete old image if exists
            if ($headerSetting && File::exists(public_path($headerSetting->value))) {
                File::delete(public_path($headerSetting->value));
            }

            // Move the file to public directory
            $file->move($uploadDir, $fileName);

            // Store just the filename, not the full path
            // This matches how setLogo() expects to find the file
            $imagePath = $fileName;

            // Update or create setting with just the filename
            LpSetting::updateOrCreate(
                ['key' => $this->headerKey],
                ['value' => $imagePath]
            );
        }
        Cache::forget('pdf_header');

        return redirect()->route('super-admin.landing-page.ads-header.index')
            ->with('success', 'Header berhasil diperbarui!');
    }

    /**
     * Update ads settings
     */
    public function updateAds(Request $request)
    {
        $request->validate([
            'ads_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:500',
        ]);

        // Get existing settings
        $adsSetting = DB::table('lp_settings')->where('key', $this->adsKey)->first();

        // Create upload directory if it doesn't exist
        $uploadDir = public_path($this->uploadPath);
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        // Handle image upload
        if ($request->hasFile('ads_image')) {
            $file = $request->file('ads_image');
            $fileName = 'ads_' . time() . '.' . $file->getClientOriginalExtension();

            // Delete old image if exists
            if ($adsSetting && File::exists(public_path($adsSetting->value))) {
                File::delete(public_path($adsSetting->value));
            }

            // Move the file to public directory
            $file->move($uploadDir, $fileName);
            $imagePath = $this->uploadPath . '/' . $fileName;

            // Update or create setting with just the path
            LpSetting::updateOrCreate(
                ['key' => $this->adsKey],
                ['value' => $imagePath]
            );
        }

        Cache::forget('pdf_ads');

        return redirect()->route('super-admin.landing-page.ads-header.index')
            ->with('success', 'Ads berhasil diperbarui!');
    }
}
