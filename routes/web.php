<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controller Auth & Public
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\KaryaController;
use App\Http\Controllers\ArticlePageController;

// Controller Siswa
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Siswa\ProjectController;
use App\Http\Controllers\Siswa\ProfilController;

// Controller Admin Jurusan
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminSiswaController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminInvitationCodeController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Controller Super Admin
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\AdminManagementController as SuperAdminManagementController;

use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\InteractionController;

Route::get('/', [PublicController::class, 'index']);

// Auth Login & Register
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Auth Google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Auth Kode Undangan
Route::get('/auth/kode-undangan', [AuthController::class, 'showKodeUndangan'])->name('auth.kode-undangan');
Route::post('/auth/kode-undangan', [AuthController::class, 'submitKodeUndangan'])->name('auth.kode-undangan.submit');

// Jembatan untuk tamu
Route::get('/login-dulu', function (Request $request) {
    $next = $request->query('next');

    if (is_string($next) && preg_match('#^/(?![/\\\\])#', $next)) {
        session()->put('url.intended', url($next));
    }

    return redirect()->route('login');
})->name('login.required');

// Public Karya, Artikel & Detail Project
Route::get('/karya', [KaryaController::class, 'index']);
Route::get('/project/{project}', [KaryaController::class, 'show'])->name('project.detail');
Route::post('/project/{project}/like', [InteractionController::class, 'toggleLike']);
Route::get('/artikel', [ArticlePageController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{slug}', [ArticlePageController::class, 'show'])->name('artikel.show');

// Tentang & Developer
Route::get('/tentang', function() {
    return view('tentang');
});
Route::get('/developer', function() {
    return view('dev');
})->name('developer');

Route::get('/u/{id}', [PublicProfileController::class, 'show'])->name('profile.show');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (SISWA & ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ==================== ROUTE SISWA ====================
    Route::get('/siswa/dashboard', [DashboardController::class, 'index']);
    Route::get('/siswa/karya', [ProjectController::class, 'index']);
    Route::get('/siswa/karya/detail/{project}', [ProjectController::class, 'show'])->name('siswa.detail-karya');
    Route::delete('/siswa/karya/{project}', [ProjectController::class, 'destroy']);
    Route::get('/siswa/upload', [ProjectController::class, 'upload']);
    Route::post('/siswa/upload', [ProjectController::class, 'store']);
    Route::get('/siswa/profil', [ProfilController::class, 'index'])->name('siswa.profil');
    Route::get('/siswa/profil/edit', [ProfilController::class, 'edit'])->name('siswa.profil.edit');
    Route::put('/siswa/profil', [ProfilController::class, 'update'])->name('siswa.profil.update');

    // ==================== ROUTE ADMIN JURUSAN ====================
    // Hanya role admin (0/1) yang boleh masuk; siswa ditolak 403
    Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
    
    // Kode Undangan (Resource Route)
    Route::resource('admin/kode-undangan', AdminInvitationCodeController::class)
        ->parameters(['kode-undangan' => 'kodeUndangan'])
        ->names('admin.kode-undangan');

    // Manajemen Karya Siswa
    Route::get('/admin/karya', [AdminProjectController::class, 'index']);
    Route::get('/admin/karya/{project}', [AdminProjectController::class, 'show']);
    Route::put('/admin/karya/{project}/update-status', [AdminProjectController::class, 'updateStatus']);
    Route::delete('/admin/karya/{project}', [AdminProjectController::class, 'destroy']);

    // Manajemen Data Siswa
    Route::get('/admin/siswa', [AdminSiswaController::class, 'index']);
    Route::put('/admin/siswa/{user}/update', [AdminSiswaController::class, 'update']);
    Route::delete('/admin/siswa/{user}', [AdminSiswaController::class, 'destroy']);

    // Manajemen Kategori
    Route::resource('admin/kategori', AdminCategoryController::class);

    // Manajemen Artikel
    Route::get('/admin/artikel', [ArticleController::class, 'index']);
    Route::resource('admin/articles', ArticleController::class);

    // Profil Admin
    Route::get('/admin/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    });

    // ==================== KOMENTAR ====================
    Route::post('/project/{project}/comment', [KaryaController::class, 'comment']);
    Route::post('/artikel/{article}/comment', [ArticlePageController::class, 'storeComment'])->name('artikel.comment');
});

// ==================== SUPER ADMIN ONLY ====================
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index']);

    // Manajemen Akun Admin
    Route::get('/manajemen-admin', [SuperAdminManagementController::class, 'index']);
    Route::post('/manajemen-admin', [SuperAdminManagementController::class, 'store']);
    Route::put('/manajemen-admin/{user}', [SuperAdminManagementController::class, 'update']);
    Route::delete('/manajemen-admin/{user}', [SuperAdminManagementController::class, 'destroy']);
});