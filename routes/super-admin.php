<?php

use App\Http\Controllers\Admin\PenilaianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Google2FAController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\SuperAdmin\AntroController;
use App\Http\Controllers\SuperAdmin\Setting\ApiController;
use App\Http\Controllers\SuperAdmin\KlinikController;
use App\Http\Controllers\SuperAdmin\Kurva\SettingController as KurvaSettingController;
use App\Http\Controllers\SuperAdmin\LandingPage\AdsHeaderController;
use App\Http\Controllers\SuperAdmin\LandingPage\BannerController as LandingPageBannerController;
use App\Http\Controllers\SuperAdmin\LandingPage\HelpController;
use App\Http\Controllers\SuperAdmin\LandingPage\LayananController as LandingPageLayananController;
use App\Http\Controllers\SuperAdmin\LandingPage\ProfileController as LandingPageProfileController;
use App\Http\Controllers\SuperAdmin\LandingPage\SkDanPpController;
use App\Http\Controllers\SuperAdmin\Langganan\PaketController;
use App\Http\Controllers\SuperAdmin\Langganan\TransaksiController;
use App\Http\Controllers\SuperAdmin\Langganan\SettingController as PoinSettingController;
use App\Http\Controllers\SuperAdmin\PasienController;
use App\Http\Controllers\SuperAdmin\Setting\PdfController as SettingPdfController;
use App\Http\Controllers\SuperAdmin\Setting\UserController as SettingUserController;
use App\Http\Controllers\SuperAdmin\TestimoniController;
use App\Http\Controllers\SuperAdmin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // Super Admin
    Route::middleware(['super-admin'])->prefix('/super-admin')->group(function () {
        // Update Contact
        // Send Email Kurva Super Admin
        Route::post('/save-chart', [PdfController::class, 'saveChart']);
        Route::post('/generate-chart-pdf/{patientId}', [PdfController::class, 'generatePdfSuperAdmin']);
        Route::post('/generate-and-send-pdf/{patientId}', [PdfController::class, 'generateAndSendPdfSuperAdmin']);
        Route::post('/generate-and-send-custom-pdf/{patientId}', [PdfController::class, 'generateAndSendCustomPdfSuperAdmin']);
        Route::post('/generate-and-send-wa/{patientId}', [PdfController::class, 'generateAndSendWaSuperAdmin']);

        Route::get('/dashboard', [DashboardController::class, 'superAdminDashoard'])->name('super-admin.dashboard');
        // Update email dan no whatsapp untuk landing page
        Route::put('/dashboard/update-contact', [DashboardController::class, 'updateContact'])->name('super-admin.dashboard.update-contact');
        // Pengguna
        Route::prefix('users')->group(function () {
            // Add this route in the super-admin group
            Route::post('add-points', [UserController::class, 'addPoints'])->name('super-admin.users.add-points');
            Route::get('', [UserController::class, 'index'])->name('super-admin.users.index');
            Route::get('create', [UserController::class, 'create'])->name('super-admin.users.create');
            Route::post('store', [UserController::class, 'store'])->name('super-admin.users.store');
            Route::get('edit/{id}', [UserController::class, 'edit'])->name('super-admin.users.edit');
            Route::put('update/{id}', [UserController::class, 'update'])->name('super-admin.users.update');
            Route::get('show/{id}', [UserController::class, 'show'])->name('super-admin.users.show');
            Route::delete('destroy/{id}', [UserController::class, 'destroy'])->name('super-admin.users.destroy');
            Route::get('show-patient/{patientId}', [UserController::class, 'showPatient'])->name('super-admin.users.show-patient');
            // Is Support Header
            Route::patch('update-is-support-header/{id}', [UserController::class, 'updateIsSupportHeader'])->name('super-admin.users.update-is-support-header');
            // Export
            Route::get('/export/users', [UserController::class, 'exportUsers'])->name('super-admin.users.export');
        });

        // Patient
        Route::prefix('patient')->group(function () {
            // Export Data Pasien
            Route::get('export', [PasienController::class, 'export'])->name('super-admin.patient.export');

            Route::get('', [PasienController::class, 'index'])->name('super-admin.patient.index');
            Route::get('show/{id}', [PasienController::class, 'show'])->name('super-admin.patient.show');
            Route::get('create', [PasienController::class, 'create'])->name('super-admin.patient.create');
            Route::post('store', [PasienController::class, 'store'])->name('super-admin.patient.store');
            Route::get('edit/{id}', [PasienController::class, 'edit'])->name('super-admin.patient.edit');
            Route::put('update/{id}', [PasienController::class, 'update'])->name('super-admin.patient.update');
            Route::delete('destroy/{id}', [PasienController::class, 'destroy'])->name('super-admin.patient.destroy')->middleware(['patient.owner']);

            // Import
            Route::get('import', [PasienController::class, 'import'])->name('super-admin.patient.import');
            Route::post('import-store', [PasienController::class, 'importStore'])->name('super-admin.patient.import-store');
            Route::get('export-template', [PasienController::class, 'exportTemplate'])->name('super-admin.patient.export-template');
            Route::get('preview/{id}', [PasienController::class, 'preview'])->name('super-admin.patient.preview');

            // Antro
            Route::prefix('antro')->group(function () {
                // Export Data Antro
                Route::get('export-all', [AntroController::class, 'exportAll'])->name('super-admin.patient.antro.export-all');
                Route::get('export/{patientId}', [AntroController::class, 'export'])->name('super-admin.patient.antro.export');

                Route::get('create/{patientId}', [AntroController::class, 'create'])->name('super-admin.patient.antro.create');
                Route::post('store/{patientId}', [AntroController::class, 'store'])->name('super-admin.patient.antro.store');
                Route::get('edit/{id}', [AntroController::class, 'edit'])->name('super-admin.patient.antro.edit');
                Route::put('update/{id}', [AntroController::class, 'update'])->name('super-admin.patient.antro.update');
                Route::delete('destroy/{id}', [AntroController::class, 'destroy'])->name('super-admin.patient.antro.destroy');

                // Update Notes
                Route::post('update-notes', [AntroController::class, 'updateNotes'])->name('super-admin.patient.antro.update-notes');

                // Import
                Route::get('import/{patientId}', [AntroController::class, 'import'])->name('super-admin.patient.antro.import');
                Route::post('import-store/{patientId}', [AntroController::class, 'importStore'])->name('super-admin.patient.antro.import-store');
                Route::get('export-template-antro', [AntroController::class, 'exportTemplate'])->name('super-admin.patient.antro.export-template');

                // Interpretasi / Penilaian
                Route::prefix('penilaian')->group(function () {
                    Route::get('', [PenilaianController::class, 'index'])->name('super-admin.patient.penilaian.index');
                    Route::post('{patientId}', [PenilaianController::class, 'store'])->name('super-admin.patient.penilaian.store');
                });
            });
        });

        // Klinik
        Route::prefix('klinik')->group(function () {
            Route::get('', [KlinikController::class, 'index'])->name('super-admin.klinik.index');
            Route::get('create', [KlinikController::class, 'create'])->name('super-admin.klinik.create');
            Route::post('store', [KlinikController::class, 'store'])->name('super-admin.klinik.store');
            Route::get('edit/{id}', [KlinikController::class, 'edit'])->name('super-admin.klinik.edit');
            Route::put('update/{id}', [KlinikController::class, 'update'])->name('super-admin.klinik.update');
            Route::patch('verifikasi/{id}', [KlinikController::class, 'verifikasi'])->name('super-admin.klinik.verifikasi');
            Route::delete('destroy/{id}', [KlinikController::class, 'destroy'])->name('super-admin.klinik.destroy');
        });

        // 2FA Setup Routes
        Route::get('/2fa/setup', [Google2FAController::class, 'setup'])->name('2fa.setup');
        Route::post('/2fa/enable', [Google2FAController::class, 'enable'])->name('2fa.enable');
        Route::get('/2fa/verify', [Google2FAController::class, 'verify'])->name('2fa.verify');
        Route::post('/2fa/validate', [Google2FAController::class, 'validateCode'])->name('2fa.validate');
        Route::post('/2fa/disable', [Google2FAController::class, 'disable'])->name('2fa.disable');

        // Kurva Setting
        Route::prefix('kurva')->group(function () {
            // Sub-menu Setting
            Route::prefix('setting')->group(function () {
                // Export Data Kurva
                Route::get('export/{tableName}/{columnName?}', [KurvaSettingController::class, 'export'])
                    ->name('super-admin.kurva.setting.export');

                Route::get('', [KurvaSettingController::class, 'index'])->name('super-admin.kurva.setting.index');
                Route::get('create', [KurvaSettingController::class, 'create'])->name('super-admin.kurva.setting.create');
                Route::get('edit/{id}', [KurvaSettingController::class, 'edit'])->name('super-admin.kurva.setting.edit');
                Route::put('update/{id}', [KurvaSettingController::class, 'update'])->name('super-admin.kurva.setting.update');
                Route::get('show/{namaTabel}', [KurvaSettingController::class, 'show'])->name('super-admin.kurva.setting.show');
            });
        });

        // Langganan
        Route::prefix('point')->group(function () {
            Route::prefix('paket')->group(function () {
                Route::get('', [PaketController::class, 'index'])->name('super-admin.langganan.paket.index');
                Route::get('create', [PaketController::class, 'create'])->name('super-admin.langganan.paket.create');
                Route::post('store', [PaketController::class, 'store'])->name('super-admin.langganan.paket.store');
                Route::get('edit/{id}', [PaketController::class, 'edit'])->name('super-admin.langganan.paket.edit');
                Route::put('update/{id}', [PaketController::class, 'update'])->name('super-admin.langganan.paket.update');
                Route::delete('destroy/{id}', [PaketController::class, 'destroy'])->name('super-admin.langganan.paket.destroy');
            });
            Route::prefix('transaksi')->group(function () {
                Route::get('', [TransaksiController::class, 'index'])->name('super-admin.langganan.transaksi.index');
                Route::get('create', [TransaksiController::class, 'create'])->name('super-admin.langganan.transaksi.create');
                Route::post('store', [TransaksiController::class, 'store'])->name('super-admin.langganan.transaksi.store');
                Route::get('show/{id}', [TransaksiController::class, 'show'])->name('super-admin.langganan.transaksi.show');
                Route::patch('update-status/{id}', [TransaksiController::class, 'updateStatus'])->name('super-admin.langganan.transaksi.update-status');
                Route::delete('destroy/{id}', [TransaksiController::class, 'destroy'])->name('super-admin.langganan.transaksi.destroy');


                // Search User by Email
                Route::get('search', [TransaksiController::class, 'searchByEmail'])->name('super-admin.langganan.paket.search');
            });

            Route::prefix('setting')->group(function () {
                Route::get('', [PoinSettingController::class, 'index'])->name('super-admin.langganan.setting.index');
                Route::get('edit/{name}', [PoinSettingController::class, 'edit'])->name('super-admin.langganan.setting.edit');
                Route::put('update/{name}', [PoinSettingController::class, 'update'])->name('super-admin.langganan.setting.update');
            });
        });

        // Testimoni
        Route::prefix('testimoni')->group(function () {
            Route::get('', [TestimoniController::class, 'index'])->name('super-admin.testimoni.index');
            Route::get('create', [TestimoniController::class, 'create'])->name('super-admin.testimoni.create');
            Route::post('store', [TestimoniController::class, 'store'])->name('super-admin.testimoni.store');
            Route::get('edit/{id}', [TestimoniController::class, 'edit'])->name('super-admin.testimoni.edit');
            Route::put('update/{id}', [TestimoniController::class, 'update'])->name('super-admin.testimoni.update');
            Route::delete('destroy/{id}', [TestimoniController::class, 'destroy'])->name('super-admin.testimoni.destroy');

            // Search User
            Route::get('search-users', [TestimoniController::class, 'searchUsers'])->name('super-admin.testimoni.search-users');
        });

        // Landing Page
        Route::prefix('landing-page')->group(function () {
            // Banner
            Route::prefix('banner')->group(function () {
                Route::get('', [LandingPageBannerController::class, 'index'])->name('super-admin.landing-page.banner.index');
                Route::put('', [LandingPageBannerController::class, 'update'])->name('super-admin.landing-page.banner.update');
                Route::delete('destroy/{id}', [LandingPageBannerController::class, 'destroy'])->name('super-admin.landing-page.banner.destroy');
            });

            // Profile
            Route::prefix('profile')->group(function () {
                Route::get('', [LandingPageProfileController::class, 'index'])->name('super-admin.landing-page.profile.index');
                Route::post('', [LandingPageProfileController::class, 'update'])->name('super-admin.landing-page.profile.update');
            });

            // Layanan
            Route::prefix('layanan')->group(function () {
                Route::get('', [LandingPageLayananController::class, 'index'])->name('super-admin.landing-page.layanan.index');
                Route::post('', [LandingPageLayananController::class, 'update'])->name('super-admin.landing-page.layanan.update');
                Route::delete('destroy/{id}', [LandingPageLayananController::class, 'destroy'])->name('super-admin.landing-page.layanan.destroy');
            });

            // Helps
            Route::prefix('help')->group(function () {
                Route::get('', [HelpController::class, 'index'])->name('super-admin.landing-page.help.index');
                Route::post('', [HelpController::class, 'update'])->name('super-admin.landing-page.help.update');
                Route::delete('destroy/{id}', [HelpController::class, 'destroy'])->name('super-admin.landing-page.help.destroy');
            });

            // Ads dan Header
            Route::prefix('ads-header')->group(function () {
                Route::get('', [AdsHeaderController::class, 'index'])->name('super-admin.landing-page.ads-header.index');
                Route::put('update-header', [AdsHeaderController::class, 'updateHeader'])->name('super-admin.landing-page.ads-header.update-header');
                Route::put('update-ads', [AdsHeaderController::class, 'updateAds'])->name('super-admin.landing-page.ads-header.update-ads');
                Route::delete('destroy-ads/{id}', [AdsHeaderController::class, 'destroyAds'])->name('super-admin.landing-page.ads-header.destroy-ads');
            });

            // SK & PP
            Route::prefix('sk-pp')->group(function () {
                Route::get('', [SkDanPpController::class, 'index'])->name('super-admin.landing-page.sk-pp.index');
                Route::put('update-sk', [SkDanPpController::class, 'updateSk'])->name('super-admin.landing-page.sk-pp.update-sk');
                Route::put('update-pp', [SkDanPpController::class, 'updatePp'])->name('super-admin.landing-page.sk-pp.update-pp');
            });
        });

        // Setting
        Route::prefix('setting')->group(function () {
            // API
            Route::prefix('api')->group(function () {
                Route::get('', [ApiController::class, 'index'])->name('super-admin.setting.api.index');
                Route::put('update', [ApiController::class, 'update'])->name('super-admin.setting.api.update');
                Route::get('delete/{key}', [ApiController::class, 'delete'])->name('super-admin.setting.api.delete');
            });

            // PDF
            Route::prefix('pdf')->group(function () {
                Route::get('', [SettingPdfController::class, 'index'])->name('super-admin.setting.pdf.index');
                Route::post('', [SettingPdfController::class, 'update'])->name('super-admin.setting.pdf.update');
                Route::get('clear-value/{id}', [SettingPdfController::class, 'clearValue'])->name('super-admin.setting.pdf.clear-value');
            });

            // User
            Route::prefix('user')->group(function () {
                Route::get('/user', [SettingUserController::class, 'index'])->name('super-admin.setting.user.index');
                Route::post('/user/update', [SettingUserController::class, 'update'])->name('super-admin.setting.user.update');
                Route::get('/user/clear-value/{id}', [SettingUserController::class, 'clearValue'])->name('super-admin.setting.user.clear-value');
            });
        });
    });
});
