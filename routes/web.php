<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DonationMonitorController;
use App\Http\Controllers\Admin\ModuleContentController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\VolunteerMonitorController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Donatur\DonationController as DonaturDonationController;
use App\Http\Controllers\Donatur\ProfileController as DonaturProfileController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Panti\DonationManagementController;
use App\Http\Controllers\Panti\NeedController;
use App\Http\Controllers\Panti\ProfileController;
use App\Http\Controllers\Panti\ReportController as PantiReportController;
use App\Http\Controllers\Panti\VolunteerManagementController;
use App\Http\Controllers\Panti\YouthController;
use App\Http\Controllers\PantiController;
use App\Http\Controllers\Relawan\ModuleController;
use App\Http\Controllers\Relawan\ReportController as RelawanReportController;
use App\Http\Controllers\Relawan\VolunteerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $pantiCount = App\Models\Panti::verified()->count();
    $needCount = App\Models\PantiNeed::active()
        ->whereHas('panti', fn ($query) => $query->verified())
        ->count();
    $volunteerReady = App\Models\ModuleAttempt::where('passed', true)->distinct()->count('user_id');
    $completedCount = App\Models\Donation::where('status', 'selesai')->count();

    $heroPanti = App\Models\Panti::verified()
        ->where('urgency_status', 'kritis')
        ->with(['needs' => fn($q) => $q->active()->orderBy('stock_days_remaining')])
        ->first();

    $heroNeed = $heroPanti?->needs->first();

    $waitingNeeds = App\Models\PantiNeed::active()
        ->whereHas('panti', fn ($query) => $query->verified())
        ->with(['panti', 'category'])
        ->orderBy('stock_days_remaining')
        ->limit(3)
        ->get();

    return view('home', compact(
        'pantiCount',
        'needCount',
        'volunteerReady',
        'completedCount',
        'heroPanti',
        'heroNeed',
        'waitingNeeds'
    ));
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->to(match (Auth::user()->role) {
            'admin' => '/admin/dashboard',
            'panti' => '/panti/dashboard',
            'relawan' => '/relawan/dashboard',
            'donatur' => '/donatur/dashboard',
            default => '/',
        });
    })->name('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.admin.index');
        })->name('dashboard');

        Route::get('/verifikasi-panti', [VerificationController::class, 'index'])->name('verification.index');
        Route::get('/verifikasi-panti/{panti}', [VerificationController::class, 'show'])->name('verification.show');
        Route::post('/verifikasi-panti/{panti}/setujui', [VerificationController::class, 'approve'])->name('verification.approve');
        Route::post('/verifikasi-panti/{panti}/tolak', [VerificationController::class, 'reject'])->name('verification.reject');

        // Pengguna (semua route mengarah ke AdminUserController)
        Route::get('/pengguna', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/pengguna/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::post('/pengguna/{user}/toggle-aktif', [AdminUserController::class, 'toggleActive'])->name('users.toggle');

        Route::get('/log-aktivitas', [ActivityLogController::class, 'index'])->name('logs.index');

        Route::get('/kategori-kebutuhan', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/kategori-kebutuhan/tambah', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/kategori-kebutuhan', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/kategori-kebutuhan/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/kategori-kebutuhan/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::post('/kategori-kebutuhan/{category}/toggle-aktif', [CategoryController::class, 'toggle'])->name('categories.toggle');

        Route::get('/modul', [AdminModuleController::class, 'index'])->name('modules.index');
        Route::get('/modul/{module:slug}', [AdminModuleController::class, 'show'])->name('modules.show');
        Route::get('/modul/{module:slug}/edit', [AdminModuleController::class, 'edit'])->name('modules.edit');
        Route::put('/modul/{module:slug}', [AdminModuleController::class, 'update'])->name('modules.update');
        Route::post('/modul/{module:slug}/toggle-publish', [AdminModuleController::class, 'toggle'])->name('modules.toggle');

        // Lesson & Quiz Modul
        Route::post('/modul/{module:slug}/lesson', [ModuleContentController::class, 'storeLesson'])->name('modules.lessons.store');
        Route::put('/modul/{module:slug}/lesson/{lesson}', [ModuleContentController::class, 'updateLesson'])->name('modules.lessons.update');
        Route::delete('/modul/{module:slug}/lesson/{lesson}', [ModuleContentController::class, 'destroyLesson'])->name('modules.lessons.destroy');
        Route::post('/modul/{module:slug}/quiz', [ModuleContentController::class, 'storeQuiz'])->name('modules.quizzes.store');
        Route::put('/modul/{module:slug}/quiz/{quiz}', [ModuleContentController::class, 'updateQuiz'])->name('modules.quizzes.update');
        Route::delete('/modul/{module:slug}/quiz/{quiz}', [ModuleContentController::class, 'destroyQuiz'])->name('modules.quizzes.destroy');

        Route::get('/donasi', [DonationMonitorController::class, 'index'])->name('donations.index');
        Route::get('/relawan', [VolunteerMonitorController::class, 'index'])->name('volunteers.index');
    });

Route::middleware(['auth', 'role:panti'])
    ->prefix('panti')
    ->name('panti.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.panti.index');
        })->name('dashboard');

        Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::match(['put', 'patch'], '/profil', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/kebutuhan', [NeedController::class, 'index'])->name('needs.index');
        Route::get('/kebutuhan/tambah', [NeedController::class, 'create'])->name('needs.create');
        Route::post('/kebutuhan', [NeedController::class, 'store'])->name('needs.store');
        Route::get('/kebutuhan/{need}/edit', [NeedController::class, 'edit'])->name('needs.edit');
        Route::put('/kebutuhan/{need}', [NeedController::class, 'update'])->name('needs.update');
        Route::delete('/kebutuhan/{need}', [NeedController::class, 'destroy'])->name('needs.destroy');

        Route::get('/donasi-masuk', [DonationManagementController::class, 'index'])->name('donations.index');
        Route::post('/donasi-masuk/{donation}/konfirmasi', [DonationManagementController::class, 'confirm'])->name('donations.confirm');
        Route::post('/donasi-masuk/{donation}/tolak', [DonationManagementController::class, 'reject'])->name('donations.reject');
        Route::post('/donasi-masuk/{donation}/selesai', [DonationManagementController::class, 'complete'])->name('donations.complete');

        Route::get('/kunjungan-relawan', [VolunteerManagementController::class, 'index'])->name('volunteers.index');
        Route::post('/kunjungan-relawan/{application}/setujui', [VolunteerManagementController::class, 'approve'])->name('volunteers.approve');
        Route::post('/kunjungan-relawan/{application}/tolak', [VolunteerManagementController::class, 'reject'])->name('volunteers.reject');

        Route::get('/youth', [YouthController::class, 'index'])->name('youth.index');
        Route::get('/youth/tambah', [YouthController::class, 'create'])->name('youth.create');
        Route::post('/youth', [YouthController::class, 'store'])->name('youth.store');
        Route::get('/youth/{youth}/edit', [YouthController::class, 'edit'])->name('youth.edit');
        Route::put('/youth/{youth}', [YouthController::class, 'update'])->name('youth.update');

        Route::get('/laporan', [PantiReportController::class, 'index'])->name('reports.index');
    });

Route::middleware(['auth', 'role:relawan'])
    ->prefix('relawan')
    ->name('relawan.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.relawan.index'))->name('dashboard');

        // Modul
        Route::get('/modul', [ModuleController::class, 'index'])->name('modules.index');
        Route::get('/modul/{module:slug}', [ModuleController::class, 'show'])->name('modules.show');
        Route::post('/modul/{module:slug}/kuis', [ModuleController::class, 'attempt'])->name('modules.attempt');

        // Pengajuan & Laporan
        Route::get('/cari-panti', [VolunteerController::class, 'search'])->name('applications.search');
        Route::get('/panti/{panti}/ajukan', [VolunteerController::class, 'create'])->name('applications.create');
        Route::post('/panti/{panti}/ajukan', [VolunteerController::class, 'store'])->name('applications.store');

        Route::get('/pengajuan-saya', [VolunteerController::class, 'index'])->name('applications.index');

        Route::get('/pengajuan/{application}/laporan', [VolunteerController::class, 'createReport'])->name('reports.create');
        Route::post('/pengajuan/{application}/laporan', [VolunteerController::class, 'storeReport'])->name('reports.store');

        Route::get('/laporan-kunjungan', [RelawanReportController::class, 'index'])->name('reports.index');
    });

Route::middleware(['auth', 'role:donatur'])
    ->name('donatur.')
    ->group(function () {
        Route::get('/donatur/dashboard', function () {
            return view('dashboard.donatur.index');
        })->name('dashboard');
        Route::get('/donatur/donasi', [DonaturDonationController::class, 'index'])->name('donations.index');

        Route::get('/panti/{panti}/donasi', [DonaturDonationController::class, 'create'])->name('donations.create');
        Route::post('/panti/{panti}/donasi', [DonaturDonationController::class, 'store'])->name('donations.store');

        Route::get('/donatur/profil', [DonaturProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/donatur/profil', [DonaturProfileController::class, 'update'])->name('profile.update');
    });

Route::get('/peta', [MapController::class, 'index'])->name('map.index');
Route::get('/peta/data', [MapController::class, 'data'])->name('map.data');

Route::get('/panti/{slug}', [PantiController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('pantis.show');

Route::get('/pantis/{slug}', [PantiController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+');

Route::prefix('api')->middleware('throttle:60,1')->group(function () {
    Route::get('/provinces', [\App\Http\Controllers\Api\LocationController::class, 'provinces']);
    Route::get('/cities', [\App\Http\Controllers\Api\LocationController::class, 'cities']);
    Route::get('/districts', [\App\Http\Controllers\Api\LocationController::class, 'districts']);
});
