<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\InstansiService;
use App\Http\Services\PointService;
use App\Http\Services\UserService;
use App\Models\LpSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected $userService;
    protected $instansiService;
    protected $pointService;

    public function __construct(UserService $userService, InstansiService $instansiService, PointService $pointService)
    {
        $this->userService = $userService;
        $this->instansiService = $instansiService;
        $this->pointService = $pointService;
    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // abort(403);
        // Get syarat ketentuan settings
        $skSetting = LpSetting::where('key', 'syarat_ketentuan')->first();
        $skFileUrl = $skSetting ? Storage::url($skSetting->value) : null;

        // Get privacy policy settings
        $ppSetting = LpSetting::where('key', 'privacy_policy')->first();
        $ppFileUrl = $ppSetting ? Storage::url($ppSetting->value) : null;

        return view('auth.register', [
            'skFileUrl' => $skFileUrl,
            'ppFileUrl' => $ppFileUrl,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // $kliniks = DB::table('instansis')->get();
        // $klinikIds = $kliniks->pluck('id')->toArray();

        $request->validate([
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => ['required', 'same:password'],
            // 'phone' => ['phone:ID'],
            // 'address' => ['required'],
            'is_nakes' => ['required', 'in:0,1'], // Accept string "0" or "1" instead of boolean
            // 'instansi' => ['required_if:is_nakes,1'],
            // 'instansi' => [
            //     Rule::requiredIf(function () use ($request) {
            //         return $request->input('is_nakes') === "1"; // Compare with string "1"
            //     }),
            //     Rule::when(function () use ($request) {
            //         return $request->input('is_nakes') === "1";
            //     }, ['in:' . implode(',', array_merge($klinikIds, ['lain-lain']))]),
            // ],
            'referral_code' => ['nullable', 'string'],
            'captcha' => ['required', 'captcha'],
            // 'terms' => ['required', 'accepted'],
        ], [
            'validation.phone' => 'Phone format is invalid.',
            'captcha.required' => 'Captcha is required.',
            'captcha.captcha' => 'Invalid captcha. Please try again.',
        ]);

        $referrerUser = $referrerInstansi = $referrerType = $instansi = null;

        // Validasi referral code
        if ($request->referral_code) {
            $code = strtoupper($request->referral_code);
            $referrerUser = $this->userService->getUserByReferralCode($code);
            $referrerInstansi = $this->instansiService->getInstansiByReferralCode($code);

            if ($referrerUser) {
                $referrerType = 'user';
            } elseif ($referrerInstansi) {
                $referrerType = 'instansi';
            } else {
                return back()->withErrors(['referral_code' => 'Kode referral tidak valid']);
            }
        }

        // Buat instansi jika pengguna adalah nakes
        if ($request->is_nakes) {
            $instansi = $this->instansiService->createInstansi([
                'name' => "Fasilitas Kesehatan ...",
                'is_verified' => true,
            ]);
            // Log::info('Creating instansi for nakes user', ['instansi_name' => $request->instansi]);
            // if ($request->instansi === 'lain-lain') {
            //     $instansi = $this->instansiService->createInstansi([
            //         'name' => "Lain-lain",
            //         'points' => 0
            //     ]);
            // } else {
            //     $instansi = $this->instansiService->getInstansiById($request->instansi);

            //     $checkUserInstansi = DB::table('users')
            //         ->where('instansi_id', $instansi->id)
            //         ->where('is_nakes', 1)
            //         ->exists();
            //     if ($checkUserInstansi) {
            //         return back()->withErrors(['instansi' => 'Tidak bisa memilih instansi ini karena sudah ada pengguna nakes lain yang terdaftar di instansi ini.']);
            //     }
            // }
            // Log::info('Instansi created', ['instansi_id' => $instansi->id]);
        }

        // Buat user baru
        $user = $this->userService->createUser([
            'name' => $request->email,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => null,
            'address' => 'INDONESIA',
            'is_nakes' => $request->is_nakes,
            'instansi_id' => $instansi?->id,
            'referrer_id' => $referrerUser?->id ?? $referrerInstansi?->id,
            'referrer_type' => $referrerType,
            'referral_code' => !$request->is_nakes ? strtoupper(Str::random(6)) : null,
        ]);

        $user->assignRole('admin');

        // Ambil setting poin berdasarkan user type
        $userType = $request->is_nakes ? 'nakes' : 'non-nakes';
        $pointReferral = $this->pointService->findSettingByNameAndUserType('REFERRAL', $userType);
        $pointReferrer = $this->pointService->findSettingByNameAndUserType('REFERRER', $userType);
        $newUserPoints = $this->pointService->findSettingByNameAndUserType('PENGGUNA-BARU', $userType);
        $expiredNewUser = calculateExpiredAt($newUserPoints->duration_type, $newUserPoints->duration);
        $expiredReferral = calculateExpiredAt($pointReferral->duration_type, $pointReferral->duration);
        $expiredReferrer = calculateExpiredAt($pointReferrer->duration_type, $pointReferrer->duration);

        DB::transaction(function () use ($user, $instansi, $referrerUser, $referrerInstansi, $referrerType, $request, $newUserPoints, $pointReferral, $pointReferrer, $expiredNewUser, $expiredReferral, $expiredReferrer) {
            if ($instansi) {
                // Batch dan transaksi untuk instansi baru
                $instansiBatch = $this->pointService->createBatch('bonus', null, $instansi->id, null, $newUserPoints->points, $newUserPoints->points, $expiredNewUser);

                $this->pointService->createTransaction(
                    null,
                    $instansi->id,
                    $instansiBatch->id,
                    $newUserPoints->id,
                    null,
                    $newUserPoints->points,
                    'bonus',
                    'Pengguna Baru',
                    null
                );

                // $instansi->points += $newUserPoints->points;
                // $instansi->save();
            }

            if (!$request->is_nakes) {
                // Batch dan transaksi untuk pengguna non-nakes baru
                $newUserBatch = $this->pointService->createBatch('bonus', $user->id, null, null, $newUserPoints->points, $newUserPoints->points, $expiredNewUser);
                $this->pointService->createTransaction(
                    $user->id,
                    null,
                    $newUserBatch->id,
                    $newUserPoints->id,
                    null,
                    $newUserPoints->points,
                    'bonus',
                    'Pengguna Baru',
                    null
                );

                // $user->points += $newUserPoints->points;
            }

            // Logika referral
            if ($request->referral_code) {
                if (!$request->is_nakes) {
                    // $totalPoint = $user->points += $pointReferral->points;
                    // Poin referral untuk pengguna baru (non-nakes)
                    $referralBatch = $this->pointService->createBatch('bonus', $user->id, null, null, $pointReferral->points, $pointReferral->points, $expiredReferral);

                    $this->pointService->createTransaction(
                        $user->id,
                        null,
                        $referralBatch->id,
                        $pointReferral->id,
                        null,
                        $pointReferral->points,
                        'referral',
                        'Referral dari ' . ($referrerUser?->name ?? $referrerInstansi?->name),
                        null
                    );

                    // $user->points = $totalPoint;
                    // $user->save();
                } else {
                    // $totalPoint = $instansi->points += $pointReferral->points;
                    // Poin referral untuk instansi (nakes)
                    $referralBatch = $this->pointService->createBatch('bonus', null, $instansi->id, null, $pointReferral->points, $pointReferral->points, $expiredReferral);
                    $this->pointService->createTransaction(
                        null,
                        $instansi->id,
                        $referralBatch->id,
                        $pointReferral->id,
                        null,
                        $pointReferral->points,
                        'referral',
                        'Referral dari ' . ($referrerUser?->name ?? $referrerInstansi?->name),
                        null
                    );
                    // $instansi->points = $totalPoint;
                    // $instansi->save();
                }

                // Poin untuk referrer
                if ($referrerType === 'user') {
                    // $totalPoint = $referrerUser->points += $pointReferrer->points;
                    $referrerBatch = $this->pointService->createBatch('bonus', $referrerUser->id, null, null, $pointReferrer->points, $pointReferrer->points, $expiredReferrer);

                    $this->pointService->createTransaction(
                        $referrerUser->id,
                        null,
                        $referrerBatch->id,
                        $pointReferrer->id,
                        null,
                        $pointReferrer->points,
                        'referrer',
                        "Bonus referral karena mengundang {$user->name}"
                    );
                    // $referrerUser->points = $totalPoint;
                    // $referrerUser->save();
                } elseif ($referrerType === 'instansi') {
                    // $totalPoint = $referrerInstansi->points += $pointReferrer->points;
                    $referrerBatch = $this->pointService->createBatch('bonus', null, $referrerInstansi->id, null, $pointReferrer->points, $pointReferrer->points, $expiredReferrer);
                    $this->pointService->createTransaction(
                        null,
                        $referrerInstansi->id,
                        $referrerBatch->id,
                        $pointReferrer->id,
                        null,
                        $pointReferrer->points,
                        'referrer',
                        "Bonus referral karena mengundang {$user->name}"
                    );
                    // $referrerInstansi->points = $totalPoint;
                    // $referrerInstansi->save();
                }
            }

            if (!$request->is_nakes) {
                $user->save();
            }
        });

        event(new Registered($user));
        Auth::login($user);

        if ($user->roles()->first()->name === 'super-admin') {
            return redirect()->intended(route('super-admin.dashboard', absolute: false));
        } elseif ($user->roles()->first()->name === 'admin') {
            return redirect()->intended(route('patient.index', absolute: false));
        }

        abort(404);
    }
}
