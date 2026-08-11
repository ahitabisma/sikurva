<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FALaravel\Google2FA;

class Google2FAController extends Controller
{
    public function setup()
    {
        $user = Auth::user();

        // If 2FA is already enabled, redirect to verification page
        if ($user->google2fa_enabled) {
            return redirect()->route('2fa.verify')
                ->with('error', '2FA already set up. Please verify your code to continue.');
        }

        // For new setup
        $google2fa = app('pragmarx.google2fa');
        $secret = $google2fa->generateSecretKey();

        $user->google2fa_secret = $secret;
        $user->save();

        return view('google2fa.index', [
            'secret' => $user->google2fa_secret,
        ]);
    }

    public function enable(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric',
        ]);

        $google2fa = app('pragmarx.google2fa');

        $secret = Auth::user()->google2fa_secret;

        if ($google2fa->verifyKey($secret, $request->one_time_password)) {
            $user = Auth::user();
            $user->google2fa_enabled = true;
            $user->save();

            return redirect()->route('super-admin.kurva.setting.index')->with('success', '2FA berhasil diaktifkan');
        } else {
            return back()->with('error', 'Kode tidak valid. Silakan coba lagi.');
        }
    }

    public function verify()
    {
        if (!Auth::user()->google2fa_enabled) {
            return redirect()->route('super-admin.kurva.setting.index');
        }

        return view('google2fa.verify');
    }

    public function validateCode(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric',
        ]);

        $google2fa = app('pragmarx.google2fa');

        $secret = Auth::user()->google2fa_secret;

        if ($google2fa->verifyKey($secret, $request->one_time_password)) {
            session(['google2fa.authenticated' => true]);

            return redirect()->route('super-admin.kurva.setting.index')->with('success', '2FA berhasil diverifikasi');
        } else {
            return back()->with('error', 'Kode tidak valid. Silakan coba lagi.');
        }
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if (Hash::check($request->password, Auth::user()->password)) {
            $user = Auth::user();
            $user->google2fa_enabled = false;
            $user->save();

            return redirect()->route('super-admin.kurva.setting.index')->with('success', '2FA berhasil dinonaktifkan');
        }

        return back()->with('error', 'Password tidak sesuai');
    }
}
