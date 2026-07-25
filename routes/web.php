<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\PublicPageController;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\Analytics;
use App\Http\Controllers\ExpressionController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExpressionController as AdminExpressionController;
use App\Http\Controllers\Admin\KonselorContactController as AdminKonselorContactController;
use App\Http\Controllers\Researcher\DashboardController as ResearcherDashboardController;

// Main Page Route
Route::get('/', [LandingPageController::class, 'index'])->name('pages-home');
Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

Route::get('/ekspresi', [ExpressionController::class, 'create'])->name('ekspresi.create');
Route::post('/ekspresi', [ExpressionController::class, 'store'])->middleware('throttle:3,60')->name('ekspresi.store');
Route::get('/ekspresi/terima-kasih', [ExpressionController::class, 'success'])->name('ekspresi.success');
Route::get('/tentang', [LandingPageController::class, 'tentang'])->name('tentang');
Route::get('/edukasi', [LandingPageController::class, 'edukasi'])->name('edukasi');
Route::get('/pencegahan', [LandingPageController::class, 'pencegahan'])->name('pencegahan');
Route::get('/terms', function() {
    return view('terms', ['terms' => Str::markdown(file_get_contents(resource_path('markdown/terms.md')))]);
})->name('terms.show');
Route::get('/policy', function() {
    return view('policy', ['policy' => Str::markdown(file_get_contents(resource_path('markdown/policy.md')))]);
})->name('policy.show');

Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role:admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('expressions/bulk-approve', [AdminExpressionController::class, 'bulkApprove'])->name('expressions.bulk-approve');
    Route::resource('expressions', AdminExpressionController::class)->only(['index', 'show', 'destroy']);
    Route::post('expressions/{expression}/approve', [AdminExpressionController::class, 'approve'])->name('expressions.approve');
    Route::post('expressions/{expression}/flag', [AdminExpressionController::class, 'flag'])->name('expressions.flag');
    Route::post('konselor/{konselor}/toggle', [AdminKonselorContactController::class, 'toggle'])->name('konselor.toggle');
    Route::resource('konselor', AdminKonselorContactController::class);
    
    // CMS Halaman Beranda / Landing Pages
    Route::resource('program-contents', \App\Http\Controllers\Admin\ProgramContentController::class)->only(['index', 'edit', 'update']);
    Route::get('audit-log', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-log.index');
    Route::post('/clear-cache', [\App\Http\Controllers\Admin\CacheController::class, 'clear'])->name('clear-cache');
});

Route::prefix('researcher')->name('researcher.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role_or_permission:researcher|admin'])->group(function () {
    Route::get('/', [ResearcherDashboardController::class, 'index'])->name('dashboard');
    Route::post('/export/request', [\App\Http\Controllers\ExportController::class, 'requestExport'])->name('export.request');
    Route::get('/export/status/{id}', [\App\Http\Controllers\ExportController::class, 'checkStatus'])->name('export.status');
    Route::get('/export/download/{id}', [\App\Http\Controllers\ExportController::class, 'download'])->name('export.download');
});

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {
  Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard');
});
