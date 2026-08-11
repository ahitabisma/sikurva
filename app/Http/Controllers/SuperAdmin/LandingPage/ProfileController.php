<?php

namespace App\Http\Controllers\SuperAdmin\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\LpProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = DB::table("lp_profiles")->first();
        return view("super-admin.landing-page.profile.index")->with([
            "title" => "Profile Settings",
            "profile" => $profile
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'description' => 'required|string',
            'skills' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Fetch existing profile or create a new one if it doesn't exist
        $profile = LpProfile::firstOrNew([]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'img/landing-page/profile/' . $fileName;
            $file->move(public_path('img/landing-page/profile'), $fileName);

            // Hapus gambar lama jika ada
            if ($profile->photo && file_exists(public_path($profile->photo))) {
                unlink(public_path($profile->photo));
            }

            $profile->photo = $filePath;
        }

        // Simpan data lainnya
        $profile->name = $request->name;
        $profile->subtitle = $request->subtitle;
        $profile->description = $request->description;
        $profile->skills = json_decode($request->skills, true);
        $profile->save();

        // Hapus cache setelah update
        Cache::forget('lp_profile');

        return back()->with('success', 'Profile berhasil diperbarui!');
    }
}
