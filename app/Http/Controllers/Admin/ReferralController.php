<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReferralMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReferralController extends Controller
{
    protected $userAuth;

    public function __construct()
    {
        $this->userAuth = Auth::user();
    }

    public function send(Request $request)
    {
        // $context = getInstansiOrUserContext($this->userAuth);

        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if email has already received a referral
        // $existingReferral = DB::table('referrals')->where('recipient_email', $request->email)->first();

        // if ($existingReferral) {
        //     return back()->with('error', 'Email ini sudah pernah menerima referral sebelumnya');
        // }

        // Create new referral
        // $referral = Referral::create([
        //     'user_id' => $context['user_id'],
        //     'instansi_id' => $context['instansi_id'],
        //     'recipient_email' => $request->email,
        //     'referral_code' => $this->userAuth->instansi->referral_code ?? $this->userAuth->referral_code ?? null,
        // ]);

        try {
            $referralCode = $this->userAuth->instansi->referral_code ?? $this->userAuth->referral_code ?? null;
            // Send email
            Mail::to($request->email)->send(new ReferralMail($this->userAuth, $referralCode, $request->email));

            // Mark as sent
            // $referral->update(['is_sent' => true]);

            return back()->with('success', "Email referral berhasil terkirim ke {$request->email} !");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan ketika mengirim email referral!');
        }
    }
}
