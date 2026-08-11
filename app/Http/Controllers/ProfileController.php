<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Services\InstansiService;
use App\Http\Services\PointService;
use App\Models\Instansi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected $instansiService;
    protected $pointService;
    protected $user;

    public function __construct(InstansiService $instansiService, PointService $pointService)
    {
        $this->pointService = $pointService;
        $this->instansiService = $instansiService;
        $this->user = User::where('id', Auth::user()->id)->first();
    }

    public function show(Request $request)
    {
        $header = ($this->user->is_nakes && $this->user->instansi ? $this->user->instansi->header : $this->user->header) ?? null;
        $shares = DB::table('nakes_collaborators')
            ->where('user_id', Auth::user()->id)
            ->where('collaborator_id', '!=', Auth::user()->id)
            ->leftJoin('users', 'nakes_collaborators.collaborator_id', '=', 'users.id')
            ->select('nakes_collaborators.*', 'users.name as collaborator_name', 'users.email as collaborator_email')
            ->orderBy('nakes_collaborators.created_at', 'desc')
            ->paginate(10);

        return view('profile.show', [
            'user' => $request->user(),
            'header' => $header,
            'shares' => $shares,
        ]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if ($request->instansi) {
            if ($request->user()->instansi) {
                $instansi = $this->instansiService->getInstansiById(Auth::user()->instansi->id);
                $instansi->name = $request->instansi;
                $instansi->save();
            } else {
                $instansi = $this->instansiService->createInstansi([
                    'name' => $request->instansi,
                ]);

                if ($instansi) {
                    $this->user->instansi_id = $instansi->id;
                    $this->user->save();
                }
            }

            // $instansi = $this->instansiService->getInstansiById(Auth::user()->instansi->id);

            // if (!$instansi->is_verified) {
            //     return Redirect::route('profile.edit')->with('error', 'Instansi Anda belum terverifikasi. Silahkan hubungi admin untuk verifikasi.');
            // }
            // $instansi->name = $request->instansi;
            // $instansi->save();
        }

        $request->user()->save();

        if (Auth::user()->roles()->first()->name == 'super-admin') {
            Cache::forget('super_admin');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's contact information.
     */
    public function updateContact(Request $request)
    {
        $validated = $request->validate(
            [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
                'no_wa' => ['required', 'phone:ID', 'max:15'],
            ],
            [
                'no_wa.phone' => 'Format nomor WA tidak valid.',
            ]
        );

        $user = Auth::user();
        $user->email = $validated['email'];
        $user->phone = $validated['no_wa'];
        $user->save();

        Cache::forget('super_admin');

        return Redirect::route('super-admin.dashboard')->with('status', 'contact-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateHeader(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        if ($user->is_nakes && !$user->instansi) {
            return back()->with('error', 'Instansi Anda belum ada. Silahkan edit profil Anda untuk menambahkan instansi.');
        }

        // Super admin bypass point system
        // $isSuperAdmin = $user->hasRole('super-admin');

        // if (!$isSuperAdmin) {
        // Get context for point system
        // $context = getInstansiOrUserContext(Auth::user());
        // $pointSetting = $this->pointService->findSettingByName('TAMBAH-HEADER');

        // // Check if user has enough points
        // $isEnough = $this->pointService->isPointEnough(
        //     $context['user_id'],
        //     $context['instansi_id'],
        //     $pointSetting->points
        // );

        // if (!$isEnough) {
        //     return redirect()
        //         ->back()
        //         ->withInput()
        //         ->with('error', 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk menambahkan header! Silahkan top up poin terlebih dahulu.');
        // }
        // }

        $request->validate([
            'header' => 'required|image|mimes:jpeg,png,jpg|max:100', // Maks 100 KB
        ]);

        // Cek apakah user adalah nakes
        $file = $request->file('header');
        $timestamp = time();
        $ext = $file->getClientOriginalExtension();

        // Set nama file unik berdasarkan tipe user
        $uniqueName = $user->is_nakes
            ? 'instansi_header_' . $user->instansi->id . '_' . $timestamp . '.' . $ext
            : 'header_' . $user->id . '_' . $timestamp . '.' . $ext;

        // Semua header disimpan di directory yang sama
        $directory = public_path('img-public/header');

        // Set old header path
        if ($user->is_nakes) {
            $oldHeader = $user->instansi->header ?? null;
        } else {
            $oldHeader = $user->header;
        }

        // Hapus file lama jika ada
        if ($oldHeader) {
            $oldPath = public_path($oldHeader);

            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        // Buat folder jika belum ada
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Simpan file baru
        $file->move($directory, $uniqueName);
        $headerPath = 'img-public/header/' . $uniqueName;

        // Simpan nama file ke database
        $updateSuccess = false;

        if ($user->is_nakes) {
            $updateSuccess = $user->instansi->update([
                'header' => $uniqueName
            ]);
        } else {
            $updateSuccess = $user->update([
                'header' => $uniqueName
            ]);
        }

        // Kurangi poin hanya jika update berhasil dan bukan super admin
        // if ($updateSuccess && !$isSuperAdmin) {
        //     $this->pointService->usage(
        //         $context['user_id'],
        //         $context['instansi_id'],
        //         $pointSetting->points,
        //         'Update Header',
        //         $pointSetting->id,
        //         null
        //     );
        // }

        // Hapus cache
        Cache::forget('header_user_' . $user->id);
        if ($user->hasRole('super-admin')) {
            Cache::forget('header_super_admin');
            Cache::forget('super_admin');
        }

        return back()->with('success', 'Header berhasil diperbarui.');
    }

    public function deleteHeader()
    {
        $user = User::find(Auth::user()->id);
        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Get the header path based on user type
        if ($user->is_nakes) {
            $header = $user->instansi->header ?? null;
        } else {
            $header = $user->header ?? null;
        }

        // If there's a header to delete
        if ($header) {
            // Delete the file from storage
            $headerPath = public_path('img-public/header/' . $header);
            if (file_exists($headerPath)) {
                @unlink($headerPath);
            }

            // Update database record
            if ($user->is_nakes) {
                $user->instansi->update(['header' => null]);
            } else {
                $user->update(['header' => null]);
            }

            // Clear cache
            Cache::forget('header_user_' . $user->id);
            if ($user->hasRole('super-admin')) {
                Cache::forget('header_super_admin');
                Cache::forget('super_admin');
            }
        }

        return back()->with('success', 'Header berhasil dihapus.');
    }

    public function updateSenderName(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        if (!$user->is_nakes) {
            return redirect()->back()->with('error', 'Fitur ini hanya tersedia untuk tenaga kesehatan.');
        }

        if (!$user->getInstansiVerified()) {
            return redirect()->back()->with('error', 'Instansi Anda belum terverifikasi. Silahkan hubungi admin untuk verifikasi.');
        }

        $user->instansi->update([
            'sender_name' => $validated['sender_name']
        ]);

        // Clear any relevant caches
        Cache::forget('instansi_' . $user->instansi->id);

        return redirect()->back()->with('success', 'Email Sender Display Name berhasil diperbarui.');
    }

    public function deleteSenderName()
    {
        $user = Auth::user();

        if (!$user->is_nakes) {
            return redirect()->back()->with('error', 'Fitur ini hanya tersedia untuk tenaga kesehatan.');
        }

        if (!$user->getInstansiVerified()) {
            return redirect()->back()->with('error', 'Instansi Anda belum terverifikasi. Silahkan hubungi admin untuk verifikasi.');
        }

        // Set sender_name to null
        $user->instansi->update([
            'sender_name' => null
        ]);

        // Clear any relevant caches
        Cache::forget('instansi_' . $user->instansi->id);

        return redirect()->back()->with('success', 'Nama pengirim berhasil dihapus.');
    }

    // public function updateKodeLokal(Request $request)
    // {
    //     $request->validate([
    //         'kode_mr' => 'required|string|size:3', // Kode lokal harus 3 karakter
    //     ]);

    //     $kode_mr = $request->input('kode_mr');

    //     // Update kode lokal berdasarkan role
    //     if ($this->user->is_nakes) {
    //         $this->user->instansi->update([
    //             'kode_lokal' => $kode_mr
    //         ]);
    //     } else {
    //         $this->user->update([
    //             'kode_lokal' => $kode_mr
    //         ]);
    //     }

    //     return Redirect::route('profile.edit')->with('status', 'profile-updated-kode-lokal');
    // }
}
