<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    HomeController,
    CustomerController,
    FacilityController,
    ProfileController,
    RoleController,
    ImmediateInformationController,
    HeroSettingController,
    EvergreenInformationController,
    InformationServiceReportController,
    InfoSlideController,
    PeriodicDocumentController,
    PpidRegulationController
};
use App\Http\Controllers\Staff_User\{
    ComplaintController,
    NewsController,
    FieldTripController,
    LaporanKeuanganController,
    PengiklananController,
    PerijinanUsahaController,
    SewaController,
    SliderController,
    TenantController,
    InformasiPublikController,
    LaluLintasController,
    LelangController,
    LetterController,
    WorkPermitController,
    NataruController,
    NataruEventController,
    DashboardNataruController,
    OjtStudentController,
};

use App\Http\Controllers\{
    ExtendAdvanceController,
    LandingPageController,
    PersuratanController,
    InventoryController,
    SandboxController,
    SlotController,
    TourismController,
    SparePartController,
    SparePartRequestController,
    StaffDashboardController,
    WorkProgramController,
    AirTrafficLogController,
    MeetingController,
    PublicMeetingController,
    PublicNataruController
};

Auth::routes();

Route::group(["prefix" => 'dashboard'], function () {
    Route::group(['middleware' => 'auth'], function () {
        /* ================== USER ROUTES ================== */

        Route::group(['prefix' => 'perizinan-kerja'], function () {
            Route::get('/', [WorkPermitController::class, 'index'])->name('kerja.userindex');
            Route::get('/create', [WorkPermitController::class, 'create'])->name('kerja.create');
            Route::post('/', [WorkPermitController::class, 'store'])->name('kerja.store');
            Route::delete('/{id}', [WorkPermitController::class, 'destroy'])->name('kerja.destroy');
            Route::get('/{workPermit}', [WorkPermitController::class, 'userShow'])->name('kerja.userShow');



        });
        //  User Routes
        Route::get('/tenant', [TenantController::class, 'indexUser'])->name('tenant.index');
        Route::get('/tenant/create', [TenantController::class, 'create'])->name('tenant.create');
        Route::post('/tenant/store', [TenantController::class, 'store'])->name('tenant.store');
        Route::delete('/tenant/{id}', [TenantController::class, 'destroy'])->name('tenant.destroy');
        Route::get('/tenant/{id}', [TenantController::class, 'show'])->name('tenant.userShow');


        Route::prefix('/sewa')->controller(SewaController::class)->group(function () {
            Route::get('/', 'index')->name('sewa.index');
            Route::get('/create', 'create')->name('sewa.create');
            Route::post('/store', 'store')->name('sewa.store');
            Route::delete('/{id}', 'destroy')->name('sewa.destroy');
            Route::get('/{id}', 'show')->name('sewa.show');

        });

        // Perijinan User Routes
        Route::get('/perijinan', [PerijinanUsahaController::class, 'indexUser'])->name('perijinan.index');
        Route::get('/perijinan/create', [PerijinanUsahaController::class, 'create'])->name('perijinan.create');
        Route::post('/perijinan/store', [PerijinanUsahaController::class, 'store'])->name('perijinan.store');
        Route::delete('/perijinan/{id}', [PerijinanUsahaController::class, 'destroy'])->name('perijinan.destroy');
        Route::get('/perijinan/{id}', [PerijinanUsahaController::class, 'show'])->name('perijinan.userShow');

        // Lelang User Routes
        Route::get('/beauty-contest', [LelangController::class, 'indexUser'])->name('lelang.index');
        Route::get('/beauty-contest/create', [LelangController::class, 'create'])->name('lelang.create');
        Route::post('/beauty-contest/store', [LelangController::class, 'store'])->name('lelang.store');
        Route::delete('/beauty-contest/{id}', [LelangController::class, 'destroy'])->name('lelang.destroy');
        Route::get('/beauty-contest/{id}', [LelangController::class, 'show'])->name('lelang.userShow');

        Route::get('/slot', [SlotController::class, 'indexUser'])->name('slot.index');
        Route::get('/slot/create', [SlotController::class, 'create'])->name('slot.create');
        Route::post('/slot/store', [SlotController::class, 'store'])->name('slot.store');
        Route::delete('/slot/{id}', [SlotController::class, 'destroy'])->name('slot.destroy');
        Route::get('/slot/{id}', [SlotController::class, 'show'])->name('slot.userShow');

        Route::get('/extend-advance', [ExtendAdvanceController::class, 'index'])->name('extend-advance.index');
        Route::get('/extend-advance/create', [ExtendAdvanceController::class, 'create'])->name('extend-advance.create');
        Route::post('/extend-advance/store', [ExtendAdvanceController::class, 'store'])->name('extend-advance.store');
        Route::delete('/extend-advance/{id}', [ExtendAdvanceController::class, 'destroy'])->name('extend-advance.destroy');
        Route::get('/extend-advance/{id}', [ExtendAdvanceController::class, 'show'])->name('extend-advance.userShow');
        Route::get('/{id}/export-pdf', [ExtendAdvanceController::class, 'exportPdf'])->name('extend-advance.export-pdf');
        Route::post('/{id}/upload-signed-document', [ExtendAdvanceController::class, 'uploadSignedDocument'])->name('extend-advance.upload-signed-document');

        // Pengiklanan User Routes
        Route::get('/pengiklanan', [PengiklananController::class, 'indexUser'])->name('pengiklanan.index');
        Route::get('/pengiklanan/create', [PengiklananController::class, 'create'])->name('pengiklanan.create');
        Route::post('/pengiklanan/store', [PengiklananController::class, 'store'])->name('pengiklanan.store');
        Route::delete('/pengiklanan/{id}', [PengiklananController::class, 'destroy'])->name('pengiklanan.destroy');
        Route::get('/pengiklanan/{id}', [PengiklananController::class, 'show'])->name('pengiklanan.userShow');

        // Pengiklanan User Routes
        Route::get('/fieldtrip', [FieldTripController::class, 'indexUser'])->name('fieldtrip.index');
        Route::get('/fieldtrip/create', [FieldTripController::class, 'create'])->name('fieldtrip.create');
        Route::post('/fieldtrip/store', [FieldTripController::class, 'store'])->name('fieldtrip.store');
        Route::delete('/fieldtrip/{id}', [FieldTripController::class, 'destroy'])->name('fieldtrip.destroy');
        Route::get('/fieldtrip/{id}', [FieldTripController::class, 'show'])->name('fieldtrip.userShow');


        Route::get('/informasi-publik', [InformasiPublikController::class, 'index'])->name('informasiPublik.index');
        Route::get('/informasi-publik/create', [InformasiPublikController::class, 'create'])->name('informasiPublik.create');
        Route::get('/informasi-publik/{id}', [InformasiPublikController::class, 'show'])->name('informasiPublik.userShow');
        Route::post('/informasi-publik/store', [InformasiPublikController::class, 'store'])->name('informasiPublik.store');
        Route::delete('/informasi-publik/{id}', [InformasiPublikController::class, 'destroy'])->name('informasiPublik.destroy');


        Route::middleware(['auth', 'staff'])->group(function () {

            Route::get('/staff', [StaffDashboardController::class, 'index'])->name('staff.dashboard.index');
            // News Staff Routes
            Route::get('staff/berita', [NewsController::class, 'index'])->name('berita.staffIndex');
            Route::get('staff/berita/create', [NewsController::class, 'create'])->name('berita.create');
            Route::get('staff/berita/{slug}', [NewsController::class, 'show'])->name('berita.show');
            Route::post('staff/berita/store', [NewsController::class, 'store'])->name('berita.store');
            Route::put('staff/berita/{id}/update', [NewsController::class, 'update'])->name('berita.update');
            Route::delete('staff/berita/{id}/destroy', [NewsController::class, 'destroy'])->name('berita.destroy');
            Route::patch('staff/berita/{id}/toggle-headline', [NewsController::class, 'toggleHeadline'])->name('berita.toggleHeadline');
            Route::patch('staff/berita/{id}/toggle-publish', [NewsController::class, 'togglePublish'])->name('berita.togglePublish');
            // Route::patch('staff/slider/{id}/toggle-visibility-footer', [NewsController::class, 'toggleVisibilityFooter'])->name('slider.toggleVisibilityFooter');

            Route::get('staff/perizinan-kerja', [WorkPermitController::class, 'index'])->name('kerja.index');
            Route::get('staff/perizinan-kerja/{workPermit}', [WorkPermitController::class, 'show'])->name('kerja.show');
            Route::patch('staff/perizinan-kerja/{workPermit}', [WorkPermitController::class, 'updateStatus'])->name('kerja.updateStatus');

            // Tenant Staff Routes
            Route::get('staff/tenant', [TenantController::class, 'index'])->name('tenant.staffIndex');
            Route::get('staff/tenant/{id}', [TenantController::class, 'show'])->name('tenant.show');
            Route::patch('staff/tenant/{tenant}', [TenantController::class, 'updateStatus'])->name('tenant.updateStatus');

            // Lelang Staff Routes
            // Route::get('staff/letter', [LetterController::class, 'index'])->name('lelang.staffIndex');
            Route::resource('staff/letter', LetterController::class)->names('letters.staff');

            Route::prefix('staff/inventories')->name('staff.inventories.')->group(function () {
                Route::get('/', [InventoryController::class, 'index'])->name('index');
                Route::get('/create', [InventoryController::class, 'create'])->name('create');
                Route::post('/', [InventoryController::class, 'store'])->name('store');
                Route::get('/{inventory}', [InventoryController::class, 'show'])->name('show'); // <<< ROUTE DETAIL BARU
                Route::get('/{inventory}/edit', [InventoryController::class, 'edit'])->name('edit');
                Route::put('/{inventory}', [InventoryController::class, 'update'])->name('update');
                Route::delete('/{inventory}', [InventoryController::class, 'destroy'])->name('destroy');
                Route::patch('/{inventory}/status', [InventoryController::class, 'updateStatus'])->name('updateStatus');
                Route::post('/{inventory}/logbook', [InventoryController::class, 'storeLogbook'])->name('storeLogbook');
                Route::get('/{inventory}/logbook/create', [InventoryController::class, 'createLogbook'])->name('createLogbook');
                Route::get('/{inventory}/logbook/{logbook}/edit', [InventoryController::class, 'editLogbook'])->name('editLogbook');
                Route::put('/{inventory}/logbook/{logbook}', [InventoryController::class, 'updateLogbook'])->name('updateLogbook');
                Route::delete('/{inventory}/logbook/{logbook}', [InventoryController::class, 'destroyLogbook'])->name('destroyLogbook');

                Route::get('/{inventory}/logbook/export', [InventoryController::class, 'exportLogbookPdf'])->name('exportLogbookPdf');
            });

            // === ROUTE BARU UNTUK PROGRAM KERJA ===
                Route::resource('staff/work-programs', WorkProgramController::class)
                    ->names('staff.work-programs');

                // Route khusus untuk update status task via AJAX
                Route::patch('staff/tasks/{task}/status', [WorkProgramController::class, 'updateTaskStatus'])
                    ->name('staff.tasks.updateStatus');

                // Rute terpisah untuk Aksi Tugas (lebih terstruktur)
                Route::prefix('staff/tasks')->name('staff.tasks.')->group(function () {
                    // Route untuk Staf mengajukan verifikasi
                    Route::post('/{task}/submit-verification', [WorkProgramController::class, 'submitTaskForVerification'])->name('submitVerification');
                    // Route untuk Kanit melakukan verifikasi
                    Route::post('/{task}/verify', [WorkProgramController::class, 'verifyTask'])->name('verify');
                });
            // Route::resource('staff/inventaris', InventoryController::class)->names('staff.inventories');
            // Route::patch('staff/inventaris/{inventory}/status', [InventoryController::class, 'updateStatus'])->name('staff.inventories.updateStatus');




            Route::prefix('staff/sewa')->controller(SewaController::class)->group(function () {
                Route::get('/', 'indexStaff')->name('staffSewa.index');
                Route::get('/{id}', 'show')->name('staffSewa.show');
                Route::patch('/{sewa}', 'updateStatus')->name('staffSewa.updateStatus');

            });

            Route::resource('staff/spare-parts', SparePartController::class)
                ->except(['show']) // Tidak ada halaman detail
                ->names('staff.spare-parts');

                // === ROUTE BARU UNTUK PERMINTAAN SUKU CADANG ===
    Route::resource('staff/spare-part-requests', SparePartRequestController::class)
        ->except(['show', 'edit', 'update']) // Hanya index, create, store, destroy
        ->names('staff.spare-part-requests');

            // Perijinan Staff Routes
            Route::get('staff/perijinan', [PerijinanUsahaController::class, 'index'])->name('perijinan.staffIndex');
            Route::get('staff/perijinan/{id}', [PerijinanUsahaController::class, 'show'])->name('perijinan.show');
            Route::patch('staff/perijinan/{license}', [PerijinanUsahaController::class, 'updateStatus'])->name('perijinan.updateStatus');

            Route::get('staff/extend-advance', [ExtendAdvanceController::class, 'indexStaff'])->name('extend-advance.staffIndex');
            Route::get('staff/extend-advance/{id}', [ExtendAdvanceController::class, 'show'])->name('extend-advance.show');
            Route::patch('staff/extend-advance/{extendAdvance}', [ExtendAdvanceController::class, 'updateStatus'])->name('extend-advance.updateStatus');
            Route::post('staff/extend-advance/settings', [ExtendAdvanceController::class, 'updateStatement'])->name('extend-advance.settings.statement.update');

            // Lelang Staff Routes
            Route::get('staff/beauty-contest', [LelangController::class, 'index'])->name('lelang.staffIndex');
            Route::get('staff/beauty-contest/{id}', [LelangController::class, 'show'])->name('lelang.show');
            Route::patch('staff/beauty-contest/{lelang}', [LelangController::class, 'updateStatus'])->name('lelang.updateStatus');

            // Slot Staff Routes
            Route::get('staff/slot', [SlotController::class, 'index'])->name('slot.staffIndex');
            Route::get('staff/slot/{id}', [SlotController::class, 'show'])->name('slot.show');
            Route::patch('staff/slot/{slot}', [SlotController::class, 'updateStatus'])->name('slot.updateStatus');


            // Pengiklanan Staff Routes
            Route::get('staff/pengiklanan', [PengiklananController::class, 'index'])->name('pengiklanan.staffIndex');
            Route::get('staff/pengiklanan/{id}', [PengiklananController::class, 'show'])->name('pengiklanan.show');
            Route::patch('staff/pengiklanan/{ad}', [PengiklananController::class, 'updateStatus'])->name('pengiklanan.updateStatus');

            // Fieldtrip Staff Routes
            Route::get('staff/fieldtrip', [FieldTripController::class, 'index'])->name('fieldtrip.staffIndex');
            Route::get('staff/fieldtrip/{id}', [FieldTripController::class, 'show'])->name('fieldtrip.show');
            Route::patch('staff/fieldtrip/{fieldtrip}', [FieldTripController::class, 'updateStatus'])->name('fieldtrip.updateStatus');

            // Pengaduan Staff Routes
            Route::get('staff/pengaduan', [ComplaintController::class, 'index'])->name('pengaduan.staffIndex');
            Route::patch('staff/pengaduan/{complaint}', [ComplaintController::class, 'updateStatus'])->name('pengaduan.staffUpdate');
            Route::delete('staff/pengaduan/{complaint}', [ComplaintController::class, 'destroy'])->name('pengaduan.Staffdestroy');



            // Finance Report Staff Routes
            Route::get('staff/keuangan', [LaporanKeuanganController::class, 'index'])->name('keuangan.staffIndex');
            Route::get('staff/keuangan/create', [LaporanKeuanganController::class, 'create'])->name('keuangan.create');
            Route::post('staff/keuangan/store', [LaporanKeuanganController::class, 'store'])->name('keuangan.store');
            Route::get('staff/keuangan/{id}/edit', [LaporanKeuanganController::class, 'edit'])->name('keuangan.edit');
            Route::put('staff/keuangan/{id}', [LaporanKeuanganController::class, 'update'])->name('keuangan.update');
            Route::delete('staff/keuangan/{id}', [LaporanKeuanganController::class, 'destroy'])->name('keuangan.destroy');

            // Public Information Staff Routes
            Route::get('staff/informasi-publik', [InformasiPublikController::class, 'index'])->name('informasiPublik.staffIndex');
            Route::get('staff/informasi-publik/{id}', [InformasiPublikController::class, 'show'])->name('informasiPublik.show');
            Route::patch('staff/informasi-publik/{id}/reply', [InformasiPublikController::class, 'reply'])->name('informasiPublik.reply');


            // Traffic Staff Routes
            Route::get('staff/lalu-lintas', [LaluLintasController::class, 'index'])->name('laluLintas.staffIndex');
            Route::get('staff/lalu-lintas/create', [LaluLintasController::class, 'create'])->name('laluLintas.create');
            Route::post('staff/lalu-lintas/store', [LaluLintasController::class, 'store'])->name('laluLintas.store');
            Route::get('staff/lalu-lintas/{id}/edit', [LaluLintasController::class, 'edit'])->name('laluLintas.edit');
            Route::put('staff/lalu-lintas/{id}', [LaluLintasController::class, 'update'])->name('laluLintas.update');
            Route::delete('staff/lalu-lintas/{id}', [LaluLintasController::class, 'destroy'])->name('laluLintas.destroy');

            Route::prefix('staff/llau-harian')->name('staff.air-traffic.')->group(function () {
                Route::get('/', [AirTrafficLogController::class, 'index'])->name('index');
                Route::get('/create', [AirTrafficLogController::class, 'create'])->name('create');
                Route::post('/', [AirTrafficLogController::class, 'store'])->name('store');
                Route::get('/{airTrafficLog}/edit', [AirTrafficLogController::class, 'edit'])->name('edit');
                Route::put('/{airTrafficLog}', [AirTrafficLogController::class, 'update'])->name('update');
                Route::delete('/{airTrafficLog}', [AirTrafficLogController::class, 'destroy'])->name('destroy');


                Route::get('/export-pdf', [AirTrafficLogController::class, 'exportPdf'])->name('exportPdf');
            });





            Route::get('staff/persuratan',[PersuratanController::class, 'index'])->name('persuratan.staffIndex');
            Route::get('staff/persuratan/create',[PersuratanController::class, 'create'])->name('persuratan.create');
            Route::post('staff/persuratan/store',[PersuratanController::class, 'store'])->name('persuratan.store');
            Route::get('staff/persuratan/{surat}',[PersuratanController::class, 'show'])->name('persuratan.show');

            // aksi workflow:
            Route::post('staff/persuratan/{surat}/verify/approve', [PersuratanController::class, 'approveVerification'])->name('persuratan.verify.approve');
            Route::post('staff/persuratan/{surat}/verify/reject',  [PersuratanController::class, 'rejectVerification'])->name('persuratan.verify.reject');
            Route::post('staff/persuratan/{surat}/revision/request',[PersuratanController::class, 'requestRevision'])->name('persuratan.revision.request');
            Route::post('staff/persuratan/{surat}/revision/submit', [PersuratanController::class, 'submitRevision'])->name('persuratan.revision.submit');
            Route::post('staff/persuratan/{surat}/final-approve',   [PersuratanController::class, 'finalApprove'])->name('persuratan.final.approve');
            Route::delete('staff/persuratan/{surat}',      [PersuratanController::class, 'destroy'])->name('persuratan.destroy');

            // === ROUTE MANAJEMEN RAPAT ABSENSI ===
            Route::prefix('staff/rapat')->name('staff.meetings.')->group(function () {
                Route::get('/', [MeetingController::class, 'index'])->name('index');
                Route::get('/create', [MeetingController::class, 'create'])->name('create'); // Route Form Tambah
                Route::post('/', [MeetingController::class, 'store'])->name('store');

                // Tambahan Route Show (Detail)
                Route::get('/{meeting}', [MeetingController::class, 'show'])->name('show');
                // Tambahan Route Tutup Absensi (Opsional)
                Route::patch('/{meeting}/toggle', [MeetingController::class, 'toggleStatus'])->name('toggle');
                Route::get('/{meeting}/export-pdf', [MeetingController::class, 'exportPdf'])->name('exportPdf');

                // <<< ROUTE BARU UNTUK EDIT & HAPUS >>>
                Route::get('/{meeting}/edit', [MeetingController::class, 'edit'])->name('edit');
                Route::put('/{meeting}', [MeetingController::class, 'update'])->name('update');
                Route::delete('/{meeting}', [MeetingController::class, 'destroy'])->name('destroy');

                Route::delete('/attendance/{attendance}', [MeetingController::class, 'destroyAttendance'])->name('destroyAttendance');
            });

            // Manajemen Posko Nataru
            Route::get('staff/posko-nataru', [NataruController::class, 'index'])->name('staff.nataru.index');
            Route::get('staff/posko-nataru/create', [NataruController::class, 'create'])->name('staff.nataru.create');
            Route::post('staff/posko-nataru/store', [NataruController::class, 'store'])->name('staff.nataru.store');
            Route::delete('staff/posko-nataru/{id}', [NataruController::class, 'destroy'])->name('staff.nataru.destroy');
            Route::resource('staff/nataru-events', NataruEventController::class)->names('staff.nataru-events');
            Route::get('staff/nataru-events/{nataruEvent}/export', [NataruEventController::class, 'exportExcel'])->name('staff.nataru-events.export');

            // Dashboard Monitoring Nataru
            Route::get('staff/nataru-dashboard', [DashboardNataruController::class, 'index'])->name('staff.nataru.dashboard');
            Route::get('staff/nataru-dashboard/data', [DashboardNataruController::class, 'getComparisonData'])->name('staff.nataru.dashboard.data');

            Route::resource('staff/ojt', OjtStudentController::class)
                ->except(['edit', 'update']) // Tambahkan edit/update jika perlu nanti
                ->names('staff.ojt');

            Route::get('staff/ojt/{student}/certificate', [OjtStudentController::class, 'exportCertificate'])
                ->name('staff.ojt.certificate');


        });


        //profile
        Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');





        /* ================== ADMIN ROUTES ================== */
        Route::group(['middleware' => 'admin'], function () {
            Route::get('/', [HomeController::class, 'root'])->name('root');


            //customers
            Route::get("customers", [CustomerController::class, "index"])->name('customers.index');
            Route::get("customers/{user}", [CustomerController::class, "show"])->name('customers.show');
            Route::delete('/customers/{user}', [CustomerController::class, 'destroy'])->name('customers.destroy');

            Route::post('customers/{user}', [CustomerController::class, 'toggleRole'])->name('customers.toggle-role');
            Route::post('customers/{user}/verify', [CustomerController::class, 'verify'])->name('customers.verify');
            Route::post('customers/{user}/unverify', [CustomerController::class, 'unverify'])->name('customers.unverify');
            Route::post('customers/{user}/toggle-staff', [CustomerController::class, 'toggleStaff'])->name('customers.toggleStaff');
            Route::put('customers/{user}/update-role', [CustomerController::class, 'updateRole'])->name('customers.updateRole');
            Route::get('customers/{user}/edit-role', [CustomerController::class, 'editRole'])->name('customers.editRole');
            Route::put('customers/{user}/update-supervisor', [CustomerController::class, 'updateSupervisor'])->name('customers.updateSupervisor');
            Route::patch('/customers/{user}/reset-password', [CustomerController::class, 'resetPassword'])->name('customers.resetPassword');

            //roles
            Route::resource('roles', RoleController::class);
            Route::resource('facilities', FacilityController::class)->names('admin.facilities');
            Route::resource('tourism', TourismController::class)->names('admin.tourism');

            // Slider Staff Routes
            Route::get('staff/slider', [SliderController::class, 'index'])->name('slider.staffIndex');
            Route::get('staff/slider/create', [SliderController::class, 'create'])->name('slider.create');
            Route::post('staff/slider/store', [SliderController::class, 'store'])->name('slider.store');
            Route::delete('staff/slider/{id}/destroy', [SliderController::class, 'destroy'])->name('slider.destroy');
            Route::patch('staff/slider/{id}/toggle-visibility-home', [SliderController::class, 'toggleVisibilityHome'])->name('slider.toggleVisibilityHome');
            Route::patch('staff/slider/{id}/toggle-visibility-footer', [SliderController::class, 'toggleVisibilityFooter'])->name('slider.toggleVisibilityFooter');

            Route::resource('info-slides', InfoSlideController::class)->names('admin.info-slides');
            Route::patch('info-slides/{infoSlide}/toggle', [InfoSlideController::class, 'toggleVisibility'])->name('admin.info-slides.toggleVisibility');

            Route::resource('staff/periodic-documents', PeriodicDocumentController::class)->names('staff.periodic-documents');
            Route::resource('staff/immediate-informations', ImmediateInformationController::class)->names('staff.immediate-informations');

            Route::resource('staff/evergreen-informations', EvergreenInformationController::class)->names('staff.evergreen-informations');
            Route::resource('staff/information-service-reports',InformationServiceReportController::class)->names('staff.information-service-reports');

            Route::resource('staff/ppid-regulations', PpidRegulationController::class)->names('staff.ppid-regulations');

            // routes/web.php (dalam grup admin/staff)
            // === ROUTE UNTUK PENGATURAN HERO ===
            Route::prefix('hero-settings')->name('admin.hero-settings.')->group(function () {
                // Halaman untuk menampilkan form pengaturan
                Route::get('/', [HeroSettingController::class, 'index'])->name('index');
                // Rute untuk memproses penyimpanan
                Route::post('/update', [HeroSettingController::class, 'update'])->name('update');
            });

        });
    });
    Route::middleware(['auth'])->group(function () {
        // Sewa Routes
        Route::get('/sewa', [SewaController::class, 'index'])->name('sewa.index');

        // Lelang Routes
        Route::get('/beauty-contest', [LelangController::class, 'index'])->name('lelang.index');

        // Perijinan Usaha Routes
        Route::get('/izin', [PerijinanUsahaController::class, 'index'])->name('izin.index');

        // Pengiklanan Routes
        Route::get('/iklan', [PengiklananController::class, 'index'])->name('iklan.index');

        // Field Trip Routes
        Route::get('/fieldtrip', [FieldTripController::class, 'index'])->name('fieldtrip.index');


        // Laporan Keuangan Routes
        Route::get('/keuangan', [LaporanKeuanganController::class, 'index'])->name('keuangan.index');

    });
});


Route::get('/', [LandingPageController::class, 'home'])->name('home');
Route::get('/lalu-lintas-angkutan', [LandingPageController::class, 'lalulintas'])->name('lalulintas');
Route::get('/api/air-freight-traffic', [LandingPageController::class, 'getAirFreightTraffic'])->name('api.air-freight-traffic');
Route::get('/api/monthly-traffic-stats', [LandingPageController::class, 'getMonthlyTrafficStats']);

Route::post('api/ai/generate-trip-plan', [LandingPageController::class, 'generateTripPlan']);
// routes/api.php
Route::get('/api/routes/domestic', [LandingPageController::class, 'getDomesticRoutesData']);

Route::get('/posko/input/{token}', [PublicNataruController::class, 'showForm'])->name('public.nataru.form');
Route::post('/posko/input/{token}', [PublicNataruController::class, 'store'])->name('public.nataru.store');

// === ROUTE BARU UNTUK TV DISPLAY ===
Route::get('/posko/tv/{token}', [PublicNataruController::class, 'tvDashboard'])->name('public.nataru.tv');

Route::get('/posko/tv/{token}/chart', [PublicNataruController::class, 'getTvChartData'])->name('public.nataru.chart');

Route::get('/keberangkatan', [LandingPageController::class, 'keberangkatan'])->name('keberangkatan');
Route::get('/kedatangan', [LandingPageController::class, 'kedatangan'])->name('kedatangan');

Route::post('/contact', [LandingPageController::class, 'submitContact'])->name('contact.submit');

Route::prefix('api')->name('api.')->group(function () {

    Route::get('/monthly-traffic-stats', [LandingPageController::class, 'getMonthlyTrafficStats'])->name('monthly-traffic-stats');
});

// API Routes
Route::prefix('api')->group(function () {
    Route::get('/image-proxy/{filename}', [LandingPageController::class, 'imageProxy'])->name('image.proxy');
    Route::get('/flight-stats', [LandingPageController::class, 'getFlightStats']);
    Route::get('/departures', [LandingPageController::class, 'getDepartures'])->name('api.departures');
    Route::get('/arrivals', [LandingPageController::class, 'getArrivals'])->name('api.arrivals');
    // routes/api.php
    Route::get('/pariwisata/unggulan', [LandingPageController::class, 'getFeaturedTourism']);

});

// Route ini bisa diakses siapa saja yang punya link
Route::get('/absensi/{slug}', [PublicMeetingController::class, 'show'])->name('public.absensi.show');
Route::post('/absensi/{slug}', [PublicMeetingController::class, 'store'])->name('public.absensi.store');



Route::get('/informasi/berita', [LandingPageController::class, 'berita'])->name('berita');
Route::get('/informasi/berita/{slug}', [LandingPageController::class, 'showNews'])->name('news.show');

Route::prefix('informasi')->group(function () {
    Route::get('/laporan-keuangan', [LandingPageController::class, 'laporanKeuangan'])->name('laporanKeuangan');
    Route::get('/laporan-keuangan/api/financial-data', [LandingPageController::class, 'getFinancialData']);
});


Route::get('/informasi-keuangan/data', [LandingPageController::class, 'getFinancialData'])->name('informasiKeuangan.data');

Route::get('/informasi/tenant', [LandingPageController::class, 'tenant'])->name('tenant');
Route::get('/informasi/sewa', [LandingPageController::class, 'sewa'])->name('sewa');
Route::get('/informasi/perijinan-usaha', [LandingPageController::class, 'perijinanUsaha'])->name('perijinanUsaha');
Route::get('/informasi/pengiklanan', [LandingPageController::class, 'pengiklanan'])->name('pengiklanan');
Route::get('/informasi/field-trip', [LandingPageController::class, 'fieldTrip'])->name('fieldTrip');
Route::get('/informasi/beauty-contest', [LandingPageController::class, 'lelang'])->name('lelang');
Route::get('/informasi/slot', [LandingPageController::class, 'slot'])->name('slot');

// GANTI DENGAN SATU ROUTE INI:
Route::get('/layanan/{service:slug}', [LandingPageController::class, 'showServicePage'])->name('layanan.show');

Route::get('/informasi-publik/profil-bandara', [LandingPageController::class, 'profilBandara'])->name('profilBandara');
Route::get('/informasi-publik/struktur-organisasi', [LandingPageController::class, 'strukturOrganisasi'])->name('strukturOrganisasi');
Route::get('/informasi-publik/profil-ppid-blu', [LandingPageController::class, 'profilPPID'])->name('profilPPID');
Route::get('/informasi-publik/pejabat-bandara', [LandingPageController::class, 'pejabatBandara'])->name('pejabatBandara');
Route::get('/informasi-publik/sop-ppid', [LandingPageController::class, 'sopPpid'])->name('sopPpid');

Route::get('/informasi-publik/laporan-layanan', [LandingPageController::class, 'laporanLayanan'])->name('laporanLayanan');
Route::get('/informasi-publik/informasi-berkala', [LandingPageController::class, 'informasiBerkala'])->name('informasiBerkala');

// routes/web.php
Route::get('/informasi-publik/informasi-serta-merta', [LandingPageController::class, 'informasiSertaMerta'])->name('informasi.serta-merta');
Route::get('/informasi-publik/informasi-setiap-saat', [LandingPageController::class, 'informasiSetiapSaat'])->name('informasi.setiap-saat');

Route::get('/informasi-publik/regulasi-ppid', [LandingPageController::class, 'regulasiPpid'])->name('regulasi.ppid');
// routes/web.php
Route::get('/informasi-publik/laporan-layanan-informasi', [LandingPageController::class, 'laporanLayananInformasi'])->name('laporan.layanan.informasi');

// routes/web.php
Route::get('/fasilitas', [LandingPageController::class, 'fasilitas'])->name('fasilitas');
Route::get('/pariwisata', [LandingPageController::class, 'pariwisata'])->name('pariwisata.index');
Route::get('/pariwisata/{slug}', [LandingPageController::class, 'detailPariwisata'])->name('pariwisata.show');

// routes/web.php

Route::prefix('regulasi')->group(function () {
    Route::get('/surat-keputusan', [LandingPageController::class, 'suratUtusan'])->name('letters.utusan');
    Route::get('/surat-edaran', [LandingPageController::class, 'suratEdaran'])->name('letters.edaran');
    Route::get('/surat-keputusan/api', [LandingPageController::class, 'getLettersUtusan'])->name('letters.utusan.api');
    Route::get('/surat-edaran/api', [LandingPageController::class, 'getLettersEdaran'])->name('letters.edaran.api');
});
// // Regulasi
// Route::get('/regulasi/surat-edaran', [LetterController::class, 'suratEdaran'])->name('suratEdaran');
// Route::get('/regulasi/surat-utusan', [LetterController::class, 'suratUtusan'])->name('suratUtusan');



//Language Translation
Route::get('/index/{locale}', [HomeController::class, 'lang']);

Route::post('/store-temp-file', [HomeController::class, 'storeTempFile'])->name('storeTempFile');
Route::post('/delete-temp-file', [HomeController::class, 'deleteTempFile'])->name('deleteTempFile');

Route::get('/get-random-customer', [SandboxController::class, 'randomCustomer'])->name('randomCustomer');


