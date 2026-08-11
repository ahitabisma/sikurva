<?php

namespace App\Http\Controllers\SuperAdmin\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\LpBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    public function index()
    {
        $banners = DB::table('lp_banners')->get()->toArray();
        return view('super-admin.landing-page.banner.index')->with([
            'title' => 'Banner Settings',
            'banners' => $banners
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'banners' => 'required|array',
            'banners.*.id' => 'nullable|integer',
            'banners.*.bg_banner_file' => 'nullable|image|max:2048',
            'banners.*.bg_banner_path' => 'nullable|string',
            'banners.*.title' => 'required|string|max:255',
            'banners.*.subtitle' => 'required|string|max:255',
        ], [
            'banners.*.bg_banner_file.image' => 'The bg banner field must be an image.',
            'banners.*.bg_banner_file.max' => 'The bg banner field must not be larger than 2 MB.',
            'banners.*.title.required' => 'The title field is required.',
            'banners.*.subtitle.required' => 'The subtitle field is required.',
        ]);

        foreach ($request->input('banners') as $index => $banner) {
            $bannerData = [
                'title' => $banner['title'],
                'subtitle' => $banner['subtitle'],
            ];

            // Jika ada file baru yang diunggah
            if ($request->hasFile("banners.$index.bg_banner_file")) {
                $file = $request->file("banners.$index.bg_banner_file");
                $fileName = time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('img/landing-page/banner'), $fileName);
                $bannerData['bg_banner'] = '/img/landing-page/banner/' . $fileName;
            } else {
                // Gunakan path lama jika tidak ada file baru
                $bannerData['bg_banner'] = $banner['bg_banner_path'] ?? "public_path('img/landing-page/banner/carousel-1.jpeg')";
            }

            // Jika ada ID, update; jika tidak, buat baru
            if (!empty($banner['id'])) {
                LpBanner::where('id', $banner['id'])->update($bannerData);
            } else {
                LpBanner::create($bannerData);
            }
        }

        // Hapus cache setelah update
        Cache::forget('lp_banners');

        return redirect()->back()->with('success', 'Banner berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $banner = LpBanner::find($id);
        if (!$banner) {
            return response()->json(['message' => 'Terjadi kesalahan banner tidak ditemukan'], 404);
        }

        // Pastikan minimal 1 banner tetap ada
        if (LpBanner::count() <= 1) {
            return response()->json(['message' => 'Tidak bisa menghapus banner karena minimal banner berjumlah 1'], 403);
        }

        if ($banner->bg_banner && File::exists(public_path($banner->bg_banner))) {
            File::delete(public_path($banner->bg_banner));
        }

        $banner->delete();

        // Hapus cache setelah delete
        Cache::forget('lp_banners');

        return response()->json(['message' => 'Banner berhasil dihapus!']);
    }
}
