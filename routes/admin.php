<?php

use App\Http\Controllers\Admin\AntroController;
use App\Http\Controllers\Admin\LanggananController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\PenilaianController;
use App\Http\Controllers\Admin\ReferralController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SharePatientController;
use App\Http\Controllers\Admin\TestimoniController;
use App\Http\Controllers\Api\MidtransWebhookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

// Midtrans Webhook for notification
Route::post('midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');

Route::middleware(['auth', 'verified'])->group(function () {

    // Send Email Kurva Admin
    Route::post('/save-chart', [PdfController::class, 'saveChart']);
    Route::post('/generate-chart-pdf/{patientId}', [PdfController::class, 'generatePdf']);
    Route::post('/generate-and-send-pdf/{patientId}', [PdfController::class, 'generateAndSendPdf']);
    Route::post('/generate-and-send-custom-pdf/{patientId}', [PdfController::class, 'generateAndSendCustomPdf']);
    Route::post('/generate-and-send-wa/{patientId}', [PdfController::class, 'generateAndSendWa']);

    // Notification routes
    Route::get('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.markAsRead');

    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.markAllAsRead');

    Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])
        ->middleware(['auth'])->name('notifications.fetch');

    // Accept Share
    Route::patch('/patient-share/{shareId}/accept/{notificationId}', [SharePatientController::class, 'acceptShare'])
        ->name('patient.share.accept');
    // Reject Share
    Route::patch('/patient-share/{shareId}/reject/{notificationId}', [SharePatientController::class, 'rejectShare'])
        ->name('patient.share.reject');
    // Stop Share
    Route::delete('/patient-share/{id}/stop', [SharePatientController::class, 'stopShare'])
        ->name('patient.share.stop');


    // Admin
    Route::middleware(['admin'])->group(function () {
        Route::get('/aktivitas', [DashboardController::class, 'adminDashboard'])->name('aktivitas.index');

        // Patient
        Route::prefix('patient')->group(function () {
            Route::get('', [PasienController::class, 'index'])->name('patient.index');
            Route::get('create', [PasienController::class, 'create'])->name('patient.create');
            Route::post('store', [PasienController::class, 'store'])->name('patient.store');
            Route::get('preview/{id}', [PasienController::class, 'preview'])->name('patient.preview')->middleware(['patient.share']);
            Route::get('edit/{id}', [PasienController::class, 'edit'])->name('patient.edit')->middleware(['patient.share']);
            Route::put('update/{id}', [PasienController::class, 'update'])->name('patient.update')->middleware(['patient.share']);
            Route::delete('destroy/{id}', [PasienController::class, 'destroy'])->name('patient.destroy')->middleware(['patient.owner']);

            // Debug PDF
            Route::post('/preview/debug-pdf/{patientId}', [PdfController::class, 'debugPdf'])->name('debug-pdf');

            // Share hanya bisa non nakes
            Route::prefix('share')->group(function () {
                Route::get('{id}', [SharePatientController::class, 'index'])->name('patient.share')->middleware(['patient.owner']);
                Route::post('{id}', [SharePatientController::class, 'store'])->name('patient.share.store')->middleware(['patient.owner']);
            });

            // Collaborator Pasien hanya bisa nakes
            Route::middleware(['is_nakes'])->group(function () {
                Route::post('collaborator', [SharePatientController::class, 'collabStore'])->name('patient.collaborator.store');

                // Accept Collaborator
                Route::patch('/collaborator/{shareId}/accept/{notificationId}', [SharePatientController::class, 'acceptCollaborator'])
                    ->name('patient.collaborator.accept');
                // Reject Collaborator
                Route::patch('/collaborator/{shareId}/reject/{notificationId}', [SharePatientController::class, 'rejectCollaborator'])
                    ->name('patient.collaborator.reject');
                // Stop Collaborator
                Route::delete('/collaborator/{id}/stop', [SharePatientController::class, 'stopCollaborator'])
                    ->name('patient.collaborator.stop');
            });

            // Import Pasien hanya bisa nakes
            Route::middleware(['is_nakes'])->group(function () {
                Route::get('import', [PasienController::class, 'import'])->name('patient.import');
                Route::post('import-store', [PasienController::class, 'importStore'])->name('patient.import-store');
                Route::get('export-template', [PasienController::class, 'exportTemplate'])->name('patient.export-template');
                // Copy ID Pasien
                Route::post('copy/{patientId}', [PasienController::class, 'copy'])->name('patient.copy');
            });

            // Antro
            Route::prefix('antro')->group(function () {
                Route::get('create/{patientId}', [AntroController::class, 'create'])->name('patient.antro.create');
                Route::post('store/{patientId}', [AntroController::class, 'store'])->name('patient.antro.store');
                Route::get('edit/{id}', [AntroController::class, 'edit'])->name('patient.antro.edit')->middleware(['patient.share']);
                Route::put('update/{id}', [AntroController::class, 'update'])->name('patient.antro.update')->middleware(['patient.share']);;
                Route::delete('destroy/{id}', [AntroController::class, 'destroy'])->name('patient.antro.destroy')->middleware(['patient.share']);

                // Update Notes
                Route::post('update-notes', [AntroController::class, 'updateNotes'])->name('patient.antro.update-notes');


                // Import (Hanya Nakes)
                Route::middleware(['is_nakes'])->group(function () {
                    Route::get('import/{patientId}', [AntroController::class, 'import'])->name('patient.antro.import');
                    Route::post('import-store/{patientId}', [AntroController::class, 'importStore'])->name('patient.antro.import-store');
                    Route::get('export-template-antro', [AntroController::class, 'exportTemplate'])->name('patient.antro.export-template');
                });

                // Interpretasi / Penilaian
                Route::prefix('penilaian')->group(function () {
                    Route::get('', [PenilaianController::class, 'index'])->name('patient.penilaian.index');
                    Route::post('{patientId}', [PenilaianController::class, 'store'])->name('patient.penilaian.store');
                });
            });
        });

        // Report
        Route::prefix('report')->group(function () {
            Route::get('', [ReportController::class, 'index'])->name('report.index');
        });

        // Referrals
        Route::prefix('referral')->group(function () {
            Route::post('', [ReferralController::class, 'send'])->name('referral.send');
        });

        // Langganan
        Route::prefix('langganan')->group(function () {

            // Payment completion pages
            Route::get('payment/finish', [LanggananController::class, 'paymentFinish'])->name('payment.finish');
            Route::get('payment/pending', [LanggananController::class, 'paymentPending'])->name('payment.pending');
            Route::get('payment/unfinish', [LanggananController::class, 'paymentUnfinish'])->name('payment.unfinish');
            Route::get('payment/error', [LanggananController::class, 'paymentError'])->name('payment.error');

            Route::get('', [LanggananController::class, 'index'])->name('langganan.index');
            Route::get('create', [LanggananController::class, 'create'])->name('langganan.create');
            Route::post('store', [LanggananController::class, 'store'])->name('langganan.store');
            Route::post('cancel/{id}', [LanggananController::class, 'cancel'])->name('langganan.cancel');
            Route::get('show/{id}', [LanggananController::class, 'show'])->name('langganan.show');

            // Add this route for getting snap token for an existing subscription
            Route::get('get-snap-token/{id}', [LanggananController::class, 'getSnapToken'])->name('langganan.get-snap-token');
        });

        // Testimoni
        Route::prefix('testimoni')->group(function () {
            Route::get('', [TestimoniController::class, 'index'])->name('testimoni.index');
            Route::get('create', [TestimoniController::class, 'create'])->name('testimoni.create');
            Route::post('store', [TestimoniController::class, 'store'])->name('testimoni.store');
            Route::get('edit', [TestimoniController::class, 'edit'])->name('testimoni.edit');
            Route::put('update', [TestimoniController::class, 'update'])->name('testimoni.update');
            Route::delete('destroy', [TestimoniController::class, 'destroy'])->name('testimoni.destroy');
        });
    });
});
