<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Http\Services\InstansiService;
use App\Http\Services\PatientService;
use App\Http\Services\PointService;
use App\Http\Services\UserService;
use App\Models\PointBatch;
use App\Models\PointTransaction;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    protected $userService;
    protected $patientService;
    protected $instansiService;
    protected $pointService;

    public function __construct(UserService $userService, PatientService $patientService, InstansiService $instansiService, PointService $pointService)
    {
        $this->userService = $userService;
        $this->patientService = $patientService;
        $this->instansiService = $instansiService;
        $this->pointService = $pointService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil ID point setting untuk download grafik
        // Cache this setting to avoid repeated lookups
        $pointSettingDownload = Cache::remember('point_setting_download', now()->addDays(7), function () {
            return $this->pointService->findSettingByName('DOWNLOAD-GRAFIK');
        });

        // Ambil ID user dengan role super-admin (bisa di-cache jika diperlukan)
        $superAdminIds = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'super-admin')
            ->where('model_has_roles.model_type', 'App\Models\User')
            ->pluck('model_has_roles.model_id');

        // Subquery: point_batches (user)
        $pointBatchUser = DB::table('point_batches')
            ->selectRaw('user_id, SUM(remaining_points) as total_points, MAX(expired_at) as latest_expired')
            ->where('expired_at', '>=', now())
            ->groupBy('user_id');

        // Subquery: point_batches (instansi)
        $pointBatchInstansi = DB::table('point_batches')
            ->selectRaw('instansi_id, SUM(remaining_points) as total_points, MAX(expired_at) as latest_expired')
            ->where('expired_at', '>=', now())
            ->groupBy('instansi_id');

        // Subquery: point_transactions (user)
        $downloadPointUser = DB::table('point_transactions')
            ->selectRaw('user_id, COUNT(id) as total_download')
            ->where('point_setting_id', $pointSettingDownload->id)
            ->groupBy('user_id');

        // Subquery: point_transactions (instansi)
        $downloadPointInstansi = DB::table('point_transactions')
            ->selectRaw('instansi_id, COUNT(id) as total_download')
            ->where('point_setting_id', $pointSettingDownload->id)
            ->groupBy('instansi_id');

        // Query utama
        $users = DB::table('users')
            ->whereNotIn('users.id', $superAdminIds)
            ->leftJoin('instansis', 'users.instansi_id', '=', 'instansis.id')
            ->leftJoin('patients', 'patients.created_by', '=', 'users.id')
            ->leftJoinSub($pointBatchUser, 'pb_user', 'users.id', '=', 'pb_user.user_id')
            ->leftJoinSub($pointBatchInstansi, 'pb_instansi', 'users.instansi_id', '=', 'pb_instansi.instansi_id')
            ->leftJoinSub($downloadPointUser, 'dp_user', 'users.id', '=', 'dp_user.user_id')
            ->leftJoinSub($downloadPointInstansi, 'dp_instansi', 'users.instansi_id', '=', 'dp_instansi.instansi_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'users.status',
                'users.instansi_id',
                'users.is_nakes',
                'users.is_support_header',
                'instansis.is_support_header as instansi_is_support_header',
                'instansis.name as instansi_name',
                DB::raw('COUNT(DISTINCT patients.id) as total_patients'),
                DB::raw('COALESCE(pb_user.total_points, 0) as total_active_points_user'),
                DB::raw('pb_user.latest_expired as user_latest_expired'),
                DB::raw('CASE
                WHEN COALESCE(pb_user.total_points, 0) = 0 THEN "Tidak Aktif"
                WHEN pb_user.latest_expired >= NOW() THEN "Aktif"
                ELSE "Tidak Aktif"
                END as point_status_user'),
                DB::raw('COALESCE(pb_instansi.total_points, 0) as total_active_points_instansi'),
                DB::raw('pb_instansi.latest_expired as instansi_latest_expired'),
                DB::raw('CASE
                WHEN COALESCE(pb_instansi.total_points, 0) = 0 THEN "Tidak Aktif"
                WHEN pb_instansi.latest_expired >= NOW() THEN "Aktif"
                ELSE "Tidak Aktif"
                END as point_status_instansi'),
                DB::raw('COALESCE(dp_user.total_download, 0) as total_download_user'),
                DB::raw('COALESCE(dp_instansi.total_download, 0) as total_download_instansi')
            )
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%")
                        ->orWhere('instansis.name', 'like', "%{$search}%");
                });
            })
            ->groupBy(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'users.status',
                'users.instansi_id',
                'users.is_nakes',
                'users.is_support_header',
                'instansis.is_support_header',
                'instansis.name',
                'pb_user.total_points',
                'pb_user.latest_expired',
                'pb_instansi.total_points',
                'pb_instansi.latest_expired',
                'dp_user.total_download',
                'dp_instansi.total_download'
            )
            ->orderByDesc('users.created_at')
            ->paginate(25);

        return view("super-admin.users.index")->with([
            'title' => 'Pengguna',
            'users' => $users,
        ]);
    }

    public function create()
    {
        $kliniks = DB::table('instansis')->where('name', '!=', 'Lain-lain')->get();
        $klinikIds = $kliniks->pluck('id')->toArray();

        return view('super-admin.users.create')->with([
            'title' => 'Tambah Pengguna',
            'kliniks' => $kliniks,
            'klinikIds' => $klinikIds,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // $kliniks = DB::table('instansis')->get();
        // $klinikIds = $kliniks->pluck('id')->toArray();

        // dd($request->all());
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['phone:ID'],
            'address' => ['required'],
            'is_nakes' => ['required', 'in:0,1'], // Accept string "0" or "1" instead of boolean
            'instansi' => ['required_if:is_nakes,1'],
            // 'instansi' => [
            //     Rule::requiredIf(function () use ($request) {
            //         return $request->input('is_nakes') === "1"; // Compare with string "1"
            //     }),
            //     Rule::when(function () use ($request) {
            //         return $request->input('is_nakes') === "1";
            //     }, ['in:' . implode(',', array_merge($klinikIds, ['lain-lain']))]),
            // ],
            'referral_code' => ['nullable', 'string'],
        ], [
            'phone.phone' => 'Format nomor telepon tidak valid.',
            // 'instansi.required_if' => 'Nama instansi wajib diisi jika Anda adalah tenaga kesehatan.',
            'instansi.in' => 'Instansi yang dipilih tidak valid.',
        ]);

        try {
            // Log::info('Starting user creation process', ['request' => $request->except('password')]);

            $referrerUser = $referrerInstansi = $referrerType = $instansi = null;

            // Validasi referral code
            if ($request->referral_code) {
                // Log::info('Validating referral code', ['code' => $request->referral_code]);
                $code = strtoupper($request->referral_code);
                $referrerUser = $this->userService->getUserByReferralCode($code);
                $referrerInstansi = $this->instansiService->getInstansiByReferralCode($code);

                if ($referrerUser) {
                    $referrerType = 'user';
                    // Log::info('Found referrer user', ['referrer_id' => $referrerUser->id]);
                } elseif ($referrerInstansi) {
                    $referrerType = 'instansi';
                    // Log::info('Found referrer instansi', ['referrer_id' => $referrerInstansi->id]);
                } else {
                    // Log::warning('Invalid referral code', ['code' => $code]);
                    return back()->withErrors(['referral_code' => 'Kode referral tidak valid']);
                }
            }

            // Buat instansi jika pengguna adalah nakes
            if ($request->is_nakes) {
                // Log::info('Creating instansi for nakes user', ['instansi_name' => $request->instansi]);
                $instansi = $this->instansiService->createInstansi([
                    'name' => $request->instansi,
                    'is_verified' => true,
                ]);
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
            // Log::info('Creating new user');
            $user = $this->userService->createUser([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'is_nakes' => $request->is_nakes,
                'instansi_id' => $instansi?->id,
                'referrer_id' => $referrerUser?->id ?? $referrerInstansi?->id,
                'referrer_type' => $referrerType,
                'referral_code' => !$request->is_nakes ? strtoupper(Str::random(6)) : null,
                'status' => true,
            ]);
            // Log::info('User created successfully', ['user_id' => $user->id]);

            // Log::info('Assigning role to user');
            $user->assignRole('admin');

            // Ambil setting poin berdasarkan user type
            $userType = $request->is_nakes ? 'nakes' : 'non-nakes';
            // Log::info('Getting point settings', ['user_type' => $userType]);

            try {
                $pointReferral = $this->pointService->findSettingByNameAndUserType('REFERRAL', $userType);
                $pointReferrer = $this->pointService->findSettingByNameAndUserType('REFERRER', $userType);
                $newUserPoints = $this->pointService->findSettingByNameAndUserType('PENGGUNA-BARU', $userType);

                // Log::info('Point settings retrieved', [
                //     'pointReferral' => $pointReferral ? $pointReferral->points : 'null',
                //     'pointReferrer' => $pointReferrer ? $pointReferrer->points : 'null',
                //     'newUserPoints' => $newUserPoints ? $newUserPoints->points : 'null'
                // ]);

                if (!$pointReferral || !$pointReferrer || !$newUserPoints) {
                    // Log::error('Point settings missing', [
                    //     'REFERRAL' => $pointReferral ? 'found' : 'missing',
                    //     'REFERRER' => $pointReferrer ? 'found' : 'missing',
                    //     'PENGGUNA-BARU' => $newUserPoints ? 'found' : 'missing',
                    // ]);
                    throw new \Exception('Point settings not configured correctly');
                }

                $expiredNewUser = calculateExpiredAt($newUserPoints->duration_type, $newUserPoints->duration);
                $expiredReferral = calculateExpiredAt($pointReferral->duration_type, $pointReferral->duration);
                $expiredReferrer = calculateExpiredAt($pointReferrer->duration_type, $pointReferrer->duration);

                // Log::info('Expiry dates calculated', [
                //     'expiredNewUser' => $expiredNewUser,
                //     'expiredReferral' => $expiredReferral,
                //     'expiredReferrer' => $expiredReferrer
                // ]);
            } catch (\Exception $e) {
                // Log::error('Error getting point settings', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                throw new \Exception('Error with point settings: ' . $e->getMessage());
            }

            // Log::info('Starting transaction for points');
            DB::transaction(function () use ($user, $instansi, $referrerUser, $referrerInstansi, $referrerType, $request, $newUserPoints, $pointReferral, $pointReferrer, $expiredNewUser, $expiredReferral, $expiredReferrer) {
                try {
                    if ($instansi) {
                        // Log::info('Creating batch and transaction for new instansi');
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
                        // Log::info('Points added to instansi', ['batch_id' => $instansiBatch->id, 'points' => $newUserPoints->points]);
                    }

                    if (!$request->is_nakes) {
                        // Log::info('Creating batch and transaction for new non-nakes user');
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
                        // Log::info('Points added to user', ['batch_id' => $newUserBatch->id, 'points' => $newUserPoints->points]);
                    }

                    // Logika referral
                    if ($request->referral_code) {
                        // Log::info('Processing referral code points');
                        if (!$request->is_nakes) {
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
                            // Log::info('Referral points added to user', ['points' => $pointReferral->points]);
                        } else {
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
                            // Log::info('Referral points added to instansi', ['points' => $pointReferral->points]);
                        }

                        // Poin untuk referrer
                        if ($referrerType === 'user') {
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
                            // Log::info('Referrer points added to user', ['referrer_id' => $referrerUser->id, 'points' => $pointReferrer->points]);
                        } elseif ($referrerType === 'instansi') {
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
                            // Log::info('Referrer points added to instansi', ['referrer_id' => $referrerInstansi->id, 'points' => $pointReferrer->points]);
                        }
                    }

                    if (!$request->is_nakes) {
                        $user->save();
                        // Log::info('Updated non-nakes user');
                    }

                    // Log::info('Transaction completed successfully');
                } catch (\Exception $e) {
                    // Log::error('Transaction error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                    throw $e;
                }
            });

            // Log::info('User creation process completed successfully');
            return redirect()->route('super-admin.users.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error in user creation', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data! ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $user = $this->userService->getUserById($id);
        return view('super-admin.users.edit')->with([
            'title' => 'Edit Nakes',
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return redirect()->route('super-admin.users.index')->with('error', 'User tidak ditemukan.');
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $id],
            'phone' => ['phone:ID'],
            'address' => ['required'],
            // 'status' => ['required', 'boolean'],
        ];

        // Add instansi validation if user is nakes
        if ($user->is_nakes) {
            $rules['instansi'] = ['required'];
        }

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        try {
            // \Log::info('Starting user update process', ['user_id' => $id]);

            $userData = [
                'name' => $request->name,
                // 'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => true,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            // Update instansi for nakes user
            if ($user->is_nakes && $request->filled('instansi')) {
                if ($user->instansi) {
                    // Update existing instansi
                    // \Log::info('Updating existing instansi', ['instansi_id' => $user->instansi->id, 'name' => $request->instansi]);
                    $this->instansiService->updateInstansi($user->instansi->id, [
                        'name' => $request->instansi
                    ]);
                } else {
                    // Create new instansi if not exists
                    // \Log::info('Creating new instansi for nakes user', ['instansi_name' => $request->instansi]);
                    $instansi = $this->instansiService->createInstansi([
                        'name' => $request->instansi,
                        'is_verified' => true
                    ]);
                    $userData['instansi_id'] = $instansi->id;

                    // Update User suscription, point batch, dan point service
                    UserSubscription::where('user_id', $request->id)
                        ->update([
                            'user_id' => null,
                            'instansi_id' => $instansi->id
                        ]);
                    PointBatch::where('user_id', $request->id)
                        ->update([
                            'user_id' => null,
                            'instansi_id' => $instansi->id
                        ]);
                    PointTransaction::where('user_id', $request->id)
                        ->update([
                            'user_id' => null,
                            'instansi_id' => $instansi->id
                        ]);
                }
            }

            // Update user
            $this->userService->updateUser($id, $userData);
            // \Log::info('User updated successfully', ['user_id' => $id]);

            return redirect()->route('super-admin.users.index')->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            // \Log::error('Error in user update', [
            //     'error' => $e->getMessage(),
            //     'file' => $e->getFile(),
            //     'line' => $e->getLine(),
            //     'trace' => $e->getTraceAsString()
            // ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return redirect()->route('super-admin.users.index')->with('error', 'User tidak ditemukan.');
        }

        try {
            if ($user->is_nakes) {
                // Hapus instansi terkait jika ada
                if ($user->instansi) {
                    $this->instansiService->deleteInstansi($user->instansi->id);
                }
            }
            $this->userService->deleteUser($id);

            return redirect()->route('super-admin.users.index')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menghapus data!');
        }
    }

    public function updateIsSupportHeader(Request $request, $id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return redirect()->route('super-admin.users.index')
                ->with('error', 'User tidak ditemukan.');
        }

        try {
            // Convert status to boolean (1/0 to true/false)
            $status = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN);

            if ($request->input('is_nakes') === 'true' && $user->instansi) {
                // For nakes users, update the instansi's is_support_header
                $user->instansi->is_support_header = $status;
                $user->instansi->save();
            } else {
                // For regular users, update their own is_support_header
                $user->is_support_header = $status;
                $user->save();
            }

            return redirect()->route('super-admin.users.index')
                ->with('success', 'Status support header berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating support header status', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return redirect()->route('super-admin.users.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui status support header!');
        }
    }

    public function show(string $id)
    {
        $user = $this->userService->getUserById($id);
        $patients = $this->patientService->getPaginated($user, 25);

        return view('super-admin.users.show')->with([
            'title' => 'Daftar Pasien ' . $user->getInstansi(),
            'user' => $user,
            'patients' => $patients
        ]);
    }

    public function showPatient(string $patientId)
    {
        $patient = $this->patientService->findById($patientId);

        return view('super-admin.users.show-patient')->with([
            'title' => 'Pasien ' . $patient->nama,
            'patient' => $patient
        ]);
    }

    public function exportUsers()
    {
        $now = now()->format('d F Y H:i:s');
        return Excel::download(new UserExport, "Daftar User Ekurva {$now}.xlsx");
    }

    /**
     * Add points manually to a user/institution.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPoints(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'duration' => 'required|numeric|min:1',
            'duration_type' => 'required|in:bulan,tahun',
        ]);

        $user = User::findOrFail($validated['user_id']);

        if ($validated['duration_type'] == 'bulan') {
            $expiredAt = now()->addMonths((int) $validated['duration']);
        } else {
            $expiredAt = now()->addYears((int) $validated['duration']);
        }

        try {
            // Determine which table to use based on is_nakes
            if ($user->is_nakes) {
                // For healthcare providers (nakes)
                $this->pointService->createBatch(
                    'bonus',
                    null,
                    $user->instansi_id,
                    null,
                    $validated['points'],
                    $validated['points'],
                    $expiredAt
                );

                $this->pointService->createTransaction(
                    null,
                    $user->instansi_id,
                    null,
                    null,
                    null,
                    $validated['points'],
                    'bonus',
                    $validated['description']
                );
            } else {
                $this->pointService->createBatch(
                    'bonus',
                    $user->id,
                    null,
                    null,
                    $validated['points'],
                    $validated['points'],
                    $expiredAt
                );

                $this->pointService->createTransaction(
                    $user->id,
                    null,
                    null,
                    null,
                    null,
                    $validated['points'],
                    'bonus',
                    $validated['description']
                );
            }

            return back()->with('success', 'Points berhasil ditambahkan ke ' . $user->name);
        } catch (\Exception $e) {
            Log::error('Error adding points', [
                'error' => $e->getMessage(),
                'user_id' => $validated['user_id'],
                'points' => $validated['points'],
                'description' => $validated['description']
            ]);
            return back()->with('error', 'Gagal menambahkan point: ' . $e->getMessage());
        }
    }
}
