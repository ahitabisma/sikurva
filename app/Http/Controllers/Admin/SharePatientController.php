<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\PatientService;
use App\Http\Services\PointService;
use App\Http\Services\UserService;
use App\Models\NakesCollaborator;
use App\Models\NonNakesPatientShare;
use App\Notifications\CollaboratorNotification;
use App\Notifications\PatientSharedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SharePatientController extends Controller
{
    protected $patientService;
    protected $userService;
    protected $pointService;
    private $maxCollaboratorsAdminNakes = 3;
    private $user;
    private $label;
    private $labelUpper;

    public function __construct(PatientService $patientService, UserService $userService, PointService $pointService)
    {
        $this->label = Auth::user()->is_nakes ? 'pasien' : 'anak';
        $this->labelUpper = ucfirst($this->label);
        $this->user = Auth::user();
        $this->patientService = $patientService;
        $this->userService = $userService;
        $this->pointService = $pointService;

        $this->maxCollaboratorsAdminNakes = Cache::rememberForever('max_collaborators_admin_nakes', function () {
            return DB::table('user_settings')
                ->where('key', 'max_collab_admin_nakes')
                ->value('value');
        });
    }

    // Non Nakes
    public function index($id)
    {
        $patient = $this->patientService->findById($id);
        $shares = DB::table('patient_shares as shares')
            ->where('shares.patient_id', $id)
            ->where('shares.shared_by', Auth::user()->id)
            ->leftJoin('users as sharedTo', 'shares.shared_to', '=', 'sharedTo.id')
            ->leftJoin('users as sharedBy', 'shares.shared_by', '=', 'sharedBy.id')
            ->select(
                'shares.*',
                'sharedTo.name as shared_to_name',
                'sharedBy.name as shared_by_name'
            )
            ->paginate(10);

        return view('admin.pasien.share.index', [
            'title' => $this->user->is_nakes ? 'Share Pasien' : 'Share Anak',
            'patient' => $patient,
            'shares' => $shares,
        ]);
    }

    // Non Nakes
    public function store(Request $request, $id)
    {
        // Get context for point system
        $context = getInstansiOrUserContext(Auth::user());
        $pointSetting = $this->pointService->findSettingByName('SHARE-PASIEN');

        // Check if user has enough points
        $isEnough = $this->pointService->isPointEnough(
            $context['user_id'],
            $context['instansi_id'],
            $pointSetting->points
        );

        if (!$isEnough) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk melakukan share data! Silahkan top up poin terlebih dahulu.');
        }

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $sharedByUser = Auth::user();
        $sharedBy = $sharedByUser->id;
        $sharedToUser = $this->userService->findByEmail($request->email);
        $sharedTo = $sharedToUser->id;

        if ($sharedToUser->hasRole('super-admin')) {
            return back()->with('error', 'Akun tidak ditemukan.');
        }

        if ($sharedBy === $sharedTo) {
            return back()->with('error', 'Anda tidak bisa membagikan data kepada diri sendiri.');
        }

        $patient = $this->patientService->findById($id);

        // Debug the patient object to see what's actually returned
        Log::debug("Patient object:", ['patient' => $patient]);

        if (!$patient) {
            return back()->with('error', "$this->labelUpper tidak ditemukan.");
        }

        $exists = NonNakesPatientShare::where('patient_id', $id)
            ->where('shared_to', $sharedTo)
            ->exists();

        if ($exists) {
            return back()->with('error', "$this->labelUpper sudah dibagikan kepada user tersebut.");
        }

        $shared = NonNakesPatientShare::create([
            'patient_id' => $id,
            'shared_by' => $sharedBy,
            'shared_to' => $sharedTo,
            'status' => 'pending',
        ]);

        // Make sure we use the correct patient model with all its attributes
        $sharedToUser->notify(new PatientSharedNotification($sharedByUser, $patient, $shared));

        // Log that a notification was sent (remove in production)
        Log::info("Notification sent to user {$sharedToUser->email} about Anak {$patient->id} - {$patient->nama}");

        // Setelah berhasil menambahkan data, kurangi poin
        if ($shared) {
            $this->pointService->usage(
                $context['user_id'],
                $context['instansi_id'],
                $pointSetting->points,
                "Share $this->labelUpper",
                $pointSetting->id,
                $patient->id,
            );

            // Log penggunaan poin
            Log::info("Poin berhasil digunakan untuk shared Anak kepada user dengan ID: {$sharedTo}", [
                'user_id' => $context['user_id'],
                'instansi_id' => $context['instansi_id'],
                'points' => $pointSetting->points,
            ]);
        }

        return back()->with('success', "$this->labelUpper berhasil dibagikan.");
    }

    // Non Nakes
    public function acceptShare(Request $request, $shareId, $notificationId)
    {
        $share = NonNakesPatientShare::findOrFail($shareId);

        $share->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            // Update the notification data to include the share status
            $data = $notification->data;
            $data['share_status'] = 'accepted';

            // Update the notification with the new data
            $notification->data = $data;
            $notification->save();

            $notification->markAsRead();

            // If there's a specific URL to redirect to based on notification type
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }

        return redirect()->back()->with('success', 'Pembagian berhasil disetujui!');
    }

    public function rejectShare(Request $request, $shareId, $notificationId)
    {
        $share = NonNakesPatientShare::findOrFail($shareId);

        $share->update([
            'status' => 'rejected',
        ]);

        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            // Update the notification data to include the share status
            $data = $notification->data;
            $data['share_status'] = 'rejected';

            // Update the notification with the new data
            $notification->data = $data;
            $notification->save();

            $notification->markAsRead();

            // If there's a specific URL to redirect to based on notification type
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }

        return redirect()->back()->with('success', 'Pembagian berhasil ditolak!');
    }

    public function stopShare($shareId)
    {
        $share = NonNakesPatientShare::findOrFail($shareId);
        $share->delete();

        return redirect()->back()->with('success', 'Pembagian berhasil dihentikan!');
    }

    public function collabStore(Request $request)
    {
        // Get context for point system
        $context = getInstansiOrUserContext(Auth::user());
        $pointSetting = $this->pointService->findSettingByNameAndUserType('KOLABORASI', 'nakes');

        // Check if user has enough points
        $isEnough = $this->pointService->isPointEnough(
            $context['user_id'],
            $context['instansi_id'],
            $pointSetting->points
        );

        if (!$isEnough) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk menambahkan collaborator! Silahkan top up poin terlebih dahulu.');
        }

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $sharedByUser = Auth::user();
        $sharedBy = $sharedByUser->id;
        $sharedToUser = $this->userService->findByEmail($request->email);
        $sharedTo = $sharedToUser->id;

        if (!$sharedToUser->is_nakes) {
            return back()->with('error_collab', 'User yang Anda invite bukan nakes.');
        }

        if ($sharedBy === $sharedTo) {
            return back()->with('error_collab', 'Anda tidak bisa membagikan pasien kepada diri sendiri.');
        }

        $exists = NakesCollaborator::where('user_id', $sharedBy)
            ->where('collaborator_id', $sharedTo)
            ->exists();

        if ($exists) {
            return back()->with('error_collab', 'Collaborator sudah ada.');
        }

        // Check if we've reached the maximum number of collaborators (3)
        $currentCollabCount = NakesCollaborator::where('user_id', $sharedBy)
            ->where('status', 'accepted')
            ->count();

        if ($currentCollabCount >= $this->maxCollaboratorsAdminNakes) {
            return back()->with('error_collab', 'Anda sudah mencapai batas maksimal 3 collaborator.');
        }

        $shared = NakesCollaborator::create([
            'user_id' => $sharedBy,
            'collaborator_id' => $sharedTo, // Fixed: Was incorrectly set to $sharedBy
            'status' => 'pending',
        ]);

        // Use the new CollaboratorNotification instead
        $sharedToUser->notify(new CollaboratorNotification($sharedByUser, $shared));

        // Log that a notification was sent
        Log::info("Collaborator invitation sent to user {$sharedToUser->email} from {$sharedByUser->name}");

        // Setelah berhasil menambahkan data, kurangi poin
        if ($shared) {
            $this->pointService->usage(
                $context['user_id'],
                $context['instansi_id'],
                $pointSetting->points,
                'Invite Collaborator',
                $pointSetting->id,
                null,
            );

            // Log penggunaan poin
            Log::info("Poin berhasil digunakan untuk menambah collaborator kepada user dengan ID: {$sharedTo}", [
                'user_id' => $context['user_id'],
                'instansi_id' => $context['instansi_id'],
                'points' => $pointSetting->points,
            ]);
        }

        return redirect()->back()->with('success_collab', 'Undangan kolaborasi berhasil dikirim.');
    }

    public function acceptCollaborator($shareId, $notificationId)
    {
        $collab = NakesCollaborator::findOrFail($shareId);

        $collab->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            // Update the notification data to include the share status
            $data = $notification->data;
            $data['share_status'] = 'accepted';

            // Update the notification with the new data
            $notification->data = $data;
            $notification->save();

            $notification->markAsRead();

            // If there's a specific URL to redirect to based on notification type
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }

        return redirect()->back()->with('success', 'Kolaborasi berhasil disetujui!');
    }

    public function rejectCollaborator(Request $request, $shareId, $notificationId)
    {
        $collab = NakesCollaborator::findOrFail($shareId);

        $collab->update([
            'status' => 'rejected',
        ]);

        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            // Update the notification data to include the share status
            $data = $notification->data;
            $data['share_status'] = 'rejected';

            // Update the notification with the new data
            $notification->data = $data;
            $notification->save();

            $notification->markAsRead();

            // If there's a specific URL to redirect to based on notification type
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }

        return redirect()->back()->with('success', 'Kolaborasi berhasil ditolak!');
    }

    public function stopCollaborator($shareId)
    {
        $collab = NakesCollaborator::findOrFail($shareId);
        $collab->delete();

        return redirect()->back()->with('success', 'Kolaborasi berhasil dihentikan!');
    }
}
