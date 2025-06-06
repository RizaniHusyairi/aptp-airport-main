<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    HomeController,
    AirlineController,
    AirportController,
    CustomerController,
    PlaneController,
    FlightController,
    ProfileController,
    TicketController,
    RoleController,
};
use App\Http\Controllers\Staff_User\{
    ComplaintController,
    NewsController,
    FieldTripController,
    LaporanKeuanganController,
    PengiklananController,
    PergudanganController,
    PerijinanUsahaController,
    SewaLahanController,
    SliderController,
    TenantController,
    InformasiPublikController,
    LaluLintasController,
    LelangController,
    LetterController,
};

use App\Http\Controllers\{
    LandingPageController,
    SandboxController,
    SidebarControler,
    SlotController

};

Auth::routes();

Route::group(["prefix" => 'dashboard'], function () {
    Route::group(['middleware' => 'auth'], function () {
        /* ================== USER ROUTES ================== */
        // Tenant User Routes
        Route::get('/tenant', [TenantController::class, 'indexUser'])->name('tenant.index');
        Route::get('/tenant/create', [TenantController::class, 'create'])->name('tenant.create');
        Route::post('/tenant/store', [TenantController::class, 'store'])->name('tenant.store');
        Route::delete('/tenant/{id}', [TenantController::class, 'destroy'])->name('tenant.destroy');
        Route::get('/tenant', [TenantController::class, 'indexUser'])->name('tenant.index');
        
        Route::prefix('/sewa')->controller(SewaLahanController::class)->group(function () {
            Route::get('/', 'index')->name('sewa.index');
            Route::get('/create', 'create')->name('sewa.create');
            Route::post('/store', 'store')->name('sewa.store');
            Route::delete('/{id}', 'destroy')->name('sewa.destroy');
        });
        
        // Perijinan User Routes
        Route::get('/perijinan', [PerijinanUsahaController::class, 'indexUser'])->name('perijinan.index');
        Route::get('/perijinan/create', [PerijinanUsahaController::class, 'create'])->name('perijinan.create');
        Route::post('/perijinan/store', [PerijinanUsahaController::class, 'store'])->name('perijinan.store');
        Route::delete('/perijinan/{id}', [PerijinanUsahaController::class, 'destroy'])->name('perijinan.destroy');
        
        // Lelang User Routes
        Route::get('/lelang', [LelangController::class, 'indexUser'])->name('lelang.index');
        Route::get('/lelang/create', [LelangController::class, 'create'])->name('lelang.create');
        Route::post('/lelang/store', [LelangController::class, 'store'])->name('lelang.store');
        Route::delete('/lelang/{id}', [LelangController::class, 'destroy'])->name('lelang.destroy');
        
        Route::get('/slot', [SlotController::class, 'indexUser'])->name('slot.index');
        Route::get('/slot/create', [SlotController::class, 'create'])->name('slot.create');
        Route::post('/slot/store', [SlotController::class, 'store'])->name('slot.store');
        Route::delete('/slot/{id}', [SlotController::class, 'destroy'])->name('slot.destroy');
        
        // Pengiklanan User Routes
        Route::get('/pengiklanan', [PengiklananController::class, 'indexUser'])->name('pengiklanan.index');
        Route::get('/pengiklanan/create', [PengiklananController::class, 'create'])->name('pengiklanan.create');
        Route::post('/pengiklanan/store', [PengiklananController::class, 'store'])->name('pengiklanan.store');
        Route::delete('/pengiklanan/{id}', [PengiklananController::class, 'destroy'])->name('pengiklanan.destroy');
        
        // Pengiklanan User Routes
        Route::get('/fieldtrip', [FieldTripController::class, 'indexUser'])->name('fieldtrip.index');
        Route::get('/fieldtrip/create', [FieldTripController::class, 'create'])->name('fieldtrip.create');
        Route::post('/fieldtrip/store', [FieldTripController::class, 'store'])->name('fieldtrip.store');
        Route::delete('/fieldtrip/{id}', [FieldTripController::class, 'destroy'])->name('fieldtrip.destroy');

        Route::middleware(['auth', 'staff'])->group(function () {
            // News Staff Routes
            Route::get('staff/berita', [NewsController::class, 'index'])->name('berita.staffIndex');
            Route::get('staff/berita/create', [NewsController::class, 'create'])->name('berita.create');
            Route::get('staff/berita/{slug}', [NewsController::class, 'show'])->name('berita.show');
            Route::post('staff/berita/store', [NewsController::class, 'store'])->name('berita.store');
            Route::delete('staff/berita/{id}/update', [NewsController::class, 'update'])->name('berita.update');
            Route::delete('staff/berita/{id}/destroy', [NewsController::class, 'destroy'])->name('berita.destroy');
            Route::patch('staff/berita/{id}/toggle-headline', [NewsController::class, 'toggleHeadline'])->name('berita.toggleHeadline');
            Route::patch('staff/berita/{id}/toggle-publish', [NewsController::class, 'togglePublish'])->name('berita.togglePublish');
            // Route::patch('staff/slider/{id}/toggle-visibility-footer', [NewsController::class, 'toggleVisibilityFooter'])->name('slider.toggleVisibilityFooter');
            
            // Tenant Staff Routes
            Route::get('staff/tenant', [TenantController::class, 'index'])->name('tenant.staffIndex');
            Route::get('staff/tenant/{id}', [TenantController::class, 'show'])->name('tenant.show');
            Route::patch('staff/tenant/{id}/approve', [TenantController::class, 'approve'])->name('tenant.approve');
            Route::patch('staff/tenant/{id}/reject', [TenantController::class, 'reject'])->name('tenant.reject');
            
            // Lelang Staff Routes
            // Route::get('staff/letter', [LetterController::class, 'index'])->name('lelang.staffIndex');
            Route::resource('staff/letters', LetterController::class)->names('letters.staff');
            Route::patch('staff/tenant/{id}/approve', [TenantController::class, 'approve'])->name('tenant.approve');
            Route::patch('staff/tenant/{id}/reject', [TenantController::class, 'reject'])->name('tenant.reject');


            Route::prefix('staff/sewa')->controller(SewaLahanController::class)->group(function () {
                Route::get('/', 'indexStaff')->name('staffSewa.index');
                Route::get('/{id}', 'show')->name('staffSewa.show');
                Route::patch('/{id}/approve', 'approve')->name('sewa.approve');
                Route::patch('/{id}/reject', 'reject')->name('sewa.reject');
            });
            
            
            // Perijinan Staff Routes
            Route::get('staff/perijinan', [PerijinanUsahaController::class, 'index'])->name('perijinan.staffIndex');
            Route::get('staff/perijinan/{id}', [PerijinanUsahaController::class, 'show'])->name('perijinan.show');
            Route::patch('staff/perijinan/{id}/approve', [PerijinanUsahaController::class, 'approve'])->name('perijinan.approve');
            Route::patch('staff/perijinan/{id}/reject', [PerijinanUsahaController::class, 'reject'])->name('perijinan.reject');
            
            // Lelang Staff Routes
            Route::get('staff/lelang', [LelangController::class, 'index'])->name('lelang.staffIndex');
            Route::get('staff/lelang/{id}', [LelangController::class, 'show'])->name('lelang.show');
            Route::patch('staff/lelang/{id}/approve', [LelangController::class, 'approve'])->name('lelang.approve');
            Route::patch('staff/lelang/{id}/reject', [LelangController::class, 'reject'])->name('lelang.reject');
            
            // Slot Staff Routes
            Route::get('staff/slot', [SlotController::class, 'index'])->name('slot.staffIndex');
            Route::get('staff/slot/{id}', [SlotController::class, 'show'])->name('slot.show');
            Route::patch('staff/slot/{id}/approve', [SlotController::class, 'approve'])->name('slot.approve');
            Route::patch('staff/slot/{id}/reject', [SlotController::class, 'reject'])->name('slot.reject');
            
            // Pengiklanan Staff Routes
            Route::get('staff/pengiklanan', [PengiklananController::class, 'index'])->name('pengiklanan.staffIndex');
            Route::get('staff/pengiklanan/{id}', [PengiklananController::class, 'show'])->name('pengiklanan.show');
            Route::patch('staff/pengiklanan/{id}/approve', [PengiklananController::class, 'approve'])->name('pengiklanan.approve');
            Route::patch('staff/pengiklanan/{id}/reject', [PengiklananController::class, 'reject'])->name('pengiklanan.reject');
            
            // Fieldtrip Staff Routes
            Route::get('staff/fieldtrip', [FieldTripController::class, 'index'])->name('fieldtrip.staffIndex');
            Route::get('staff/fieldtrip/{id}', [FieldTripController::class, 'show'])->name('fieldtrip.show');
            Route::patch('staff/fieldtrip/{id}/approve', [FieldTripController::class, 'approve'])->name('fieldtrip.approve');
            Route::patch('staff/fieldtrip/{id}/reject', [FieldTripController::class, 'reject'])->name('fieldtrip.reject');
            
            // Pengaduan Staff Routes
            Route::get('staff/pengaduan', [ComplaintController::class, 'index'])->name('pengaduan.staffIndex');
            Route::patch('staff/pengaduan/{complaint}', [ComplaintController::class, 'updateStatus'])->name('pengaduan.staffUpdate');
            Route::delete('staff/pengaduan/{complaint}', [ComplaintController::class, 'destroy'])->name('pengaduan.Staffdestroy');
            
            // Slider Staff Routes
            Route::get('staff/slider', [SliderController::class, 'index'])->name('slider.staffIndex');
            Route::get('staff/slider/create', [SliderController::class, 'create'])->name('slider.create');
            Route::post('staff/slider/store', [SliderController::class, 'store'])->name('slider.store');
            Route::delete('staff/slider/{id}/destroy', [SliderController::class, 'destroy'])->name('slider.destroy');
            Route::patch('staff/slider/{id}/toggle-visibility-home', [SliderController::class, 'toggleVisibilityHome'])->name('slider.toggleVisibilityHome');
            Route::patch('staff/slider/{id}/toggle-visibility-footer', [SliderController::class, 'toggleVisibilityFooter'])->name('slider.toggleVisibilityFooter');
            
            // Finance Report Staff Routes
            Route::get('staff/keuangan', [LaporanKeuanganController::class, 'index'])->name('keuangan.staffIndex');
            Route::get('staff/keuangan/create', [LaporanKeuanganController::class, 'create'])->name('keuangan.create');
            Route::post('staff/keuangan/store', [LaporanKeuanganController::class, 'store'])->name('keuangan.store');
            
            // Public Information Staff Routes
            Route::get('staff/informasi-publik', [InformasiPublikController::class, 'index'])->name('informasiPublik.staffIndex');
            Route::get('staff/informasi-publik/{id}', [InformasiPublikController::class, 'show'])->name('informasiPublik.show');
            Route::patch('staff/informasi-publik/{id}/reply', [InformasiPublikController::class, 'reply'])->name('informasiPublik.reply');

            
            // Traffic Staff Routes
            Route::get('staff/lalu-lintas', [LaluLintasController::class, 'index'])->name('laluLintas.staffIndex');
            Route::get('staff/lalu-lintas/create', [LaluLintasController::class, 'create'])->name('laluLintas.create');
            Route::post('staff/lalu-lintas/store', [LaluLintasController::class, 'store'])->name('laluLintas.store');
        });


        //profile
        Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');


        //tickets
        Route::get('tickets/show-flights', [TicketController::class, 'showFlights'])->name('tickets.flights');
        Route::get('tickets/user-tickets', [TicketController::class, 'userTickets'])->name('tickets.userTickets');
        Route::post('tickets/book', [TicketController::class, 'book'])->name('tickets.book');
        Route::post('tickets/cancel-flight', [TicketController::class, 'cancel'])->name('tickets.cancel');



        /* ================== ADMIN ROUTES ================== */
        Route::group(['middleware' => 'admin'], function () {
            Route::get('/', [HomeController::class, 'root'])->name('root');

            //get count of tickets
            Route::get('/ticket-status-count', [SidebarControler::class, 'ticketStatusCount'])->name('ticketStatusCount');

            //airlines
            Route::resource("airlines", AirlineController::class);

            //planes
            Route::resource("planes", PlaneController::class)->except('show');

            //airports
            Route::resource("airports", AirportController::class)->except('show');

            //flights
            Route::get("flights/get-planes-by-airline", [FlightController::class, 'getPlanesByAirline'])->name('flights.getPlanesByAirline');
            Route::resource("flights", FlightController::class)->except('show');

            //tickets
            Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
            Route::post('tickets/change-status/{ticket}', [TicketController::class, 'changeStatus'])->name('tickets.changeStatus');

            //customers
            Route::get("customers", [CustomerController::class, "index"])->name('customers.index');
            Route::get("customers/{user}", [CustomerController::class, "show"])->name('customers.show');
            Route::post('customers/{user}', [CustomerController::class, 'toggleRole'])->name('customers.toggle-role');
            Route::post('customers/{user}/verify', [CustomerController::class, 'verify'])->name('customers.verify');
            Route::post('customers/{user}/unverify', [CustomerController::class, 'unverify'])->name('customers.unverify');
            Route::post('customers/{user}/toggle-staff', [CustomerController::class, 'toggleStaff'])->name('customers.toggleStaff');
            Route::put('customers/{user}/update-role', [CustomerController::class, 'updateRole'])->name('customers.updateRole');
            Route::get('customers/{user}/edit-role', [CustomerController::class, 'editRole'])->name('customers.editRole');
            Route::resource('customers', CustomerController::class);

            //roles
            Route::resource('roles', RoleController::class);
        });
    });
    Route::middleware(['auth'])->group(function () {
        // Sewa Routes
        Route::get('/sewa', [SewaLahanController::class, 'index'])->name('sewa.index');
        
        // Lelang Routes
        Route::get('/lelang', [LelangController::class, 'index'])->name('lelang.index');
        
        // Perijinan Usaha Routes
        Route::get('/izin', [PerijinanUsahaController::class, 'index'])->name('izin.index');
        
        // Pengiklanan Routes
        Route::get('/iklan', [PengiklananController::class, 'index'])->name('iklan.index');
        
        // Field Trip Routes
        Route::get('/fieldtrip', [FieldTripController::class, 'index'])->name('fieldtrip.index');
        
        // Pergudangan Routes
        Route::get('/gudang', [PergudanganController::class, 'index'])->name('gudang.index');
        
        // Laporan Keuangan Routes
        Route::get('/keuangan', [LaporanKeuanganController::class, 'index'])->name('keuangan.index');
        
        // Slider Routes
        Route::get('/slider', [SliderController::class, 'index'])->name('slider.index');
    });
});


Route::get('/', [LandingPageController::class, 'home'])->name('home');
Route::get('/lalu-lintas-angkutan', [LandingPageController::class, 'lalulintas'])->name('lalulintas');
Route::get('/api/air-freight-traffic', [LandingPageController::class, 'getTrafficData'])->name('api.air-freight-traffic');

Route::get('/keberangkatan', [LandingPageController::class, 'keberangkatan'])->name('keberangkatan');
Route::get('/kedatangan', [LandingPageController::class, 'kedatangan'])->name('kedatangan');

Route::post('/contact', [LandingPageController::class, 'submitContact'])->name('contact.submit');
// Route::post('/pengaduan', [LandingPageController::class, 'storePengaduan'])->name('pengaduan.store');



// API Routes
Route::prefix('api')->group(function () {
    Route::get('/flight-stats', [LandingPageController::class, 'getFlightStats']);
    Route::get('/departures', [LandingPageController::class, 'getDepartures'])->name('api.departures');
    Route::get('/arrivals', [LandingPageController::class, 'getArrivals'])->name('api.arrivals');
    
});


Route::get('/informasi/berita', [LandingPageController::class, 'berita'])->name('berita');
Route::get('/informasi/berita/{slug}', [LandingPageController::class, 'showNews'])->name('news.show');

Route::prefix('informasi')->group(function () {
    Route::get('/laporan-keuangan', [LandingPageController::class, 'laporanKeuangan'])->name('laporanKeuangan');
    Route::get('/laporan-keuangan/api/financial-data', [LandingPageController::class, 'getFinancialData']);
});

Route::get('/informasi-keuangan/data', [LandingPageController::class, 'getFinanceData'])->name('informasiKeuangan.data');

Route::get('/informasi/tenant', [LandingPageController::class, 'tenant'])->name('tenant');
Route::get('/informasi/sewa', [LandingPageController::class, 'sewa'])->name('sewa');
Route::get('/informasi/perijinan-usaha', [LandingPageController::class, 'perijinanUsaha'])->name('perijinanUsaha');
Route::get('/informasi/pengiklanan', [LandingPageController::class, 'pengiklanan'])->name('pengiklanan');
Route::get('/informasi/field-trip', [LandingPageController::class, 'fieldTrip'])->name('fieldTrip');
Route::get('/informasi/lelang', [LandingPageController::class, 'lelang'])->name('lelang');
Route::get('/informasi/slot', [LandingPageController::class, 'slot'])->name('slot');

Route::get('/informasi-publik/profil-bandara', [LandingPageController::class, 'profilBandara'])->name('profilBandara');
Route::get('/informasi-publik/struktur-organisasi', [LandingPageController::class, 'strukturOrganisasi'])->name('strukturOrganisasi');
Route::get('/informasi-publik/profil-ppid-blu', [LandingPageController::class, 'profilPPID'])->name('profilPPID');
Route::get('/informasi-publik/pejabat-bandara', [LandingPageController::class, 'pejabatBandara'])->name('pejabatBandara');
Route::get('/informasi-publik/sop-ppid', [LandingPageController::class, 'sopPpid'])->name('sopPpid');
Route::get('/informasi-publik/pengajuan-informasi-publik', [LandingPageController::class, 'pengajuanInformasiPublik'])->name('pengajuanInformasiPublik');
Route::post('/informasi-publik/pengajuan-informasi-publik', [LandingPageController::class, 'storePengajuanInformasiPublik'])->name('storePengajuanInformasiPublik');


Route::prefix('regulasi')->group(function () {
    Route::get('/surat-utusan', [LandingPageController::class, 'suratUtusan'])->name('letters.utusan');
    Route::get('/surat-edaran', [LandingPageController::class, 'suratEdaran'])->name('letters.edaran');
    Route::get('/surat-utusan/api', [LandingPageController::class, 'getLettersUtusan'])->name('letters.utusan.api');
    Route::get('/surat-edaran/api', [LandingPageController::class, 'getLettersEdaran'])->name('letters.edaran.api');
});
// // Regulasi
// Route::get('/regulasi/surat-edaran', [LetterController::class, 'suratEdaran'])->name('suratEdaran');
// Route::get('/regulasi/surat-utusan', [LetterController::class, 'suratUtusan'])->name('suratUtusan');

//Language Translation
Route::get('/keberangkatan', [LandingPageController::class, 'keberangkatan'])->name('keberangkatan');
Route::get('/kedatangan', [LandingPageController::class, 'kedatangan'])->name('kedatangan');
Route::get('/lalu-lintas-angkutan-udara', [LandingPageController::class, 'laluLintas'])->name('laluLintas');

//Language Translation
Route::get('/index/{locale}', [HomeController::class, 'lang']);

Route::post('/store-temp-file', [HomeController::class, 'storeTempFile'])->name('storeTempFile');
Route::post('/delete-temp-file', [HomeController::class, 'deleteTempFile'])->name('deleteTempFile');

Route::get('/get-random-customer', [SandboxController::class, 'randomCustomer'])->name('randomCustomer');

//render files inside views/template folder
Route::get('{any}', [HomeController::class, 'index'])->name('index');
