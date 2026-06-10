<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\ComplaintCategoryController;
use App\Http\Controllers\Admin\ComplaintResponseController;
use App\Http\Controllers\Admin\ComplaintReportController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomComplaintReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Public\ComplaintController;
use App\Http\Controllers\Public\TrackingController;
use App\Models\ComplaintCategory;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Area
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'categories' => ComplaintCategory::select('id', 'name')->get()
    ]);
});

Route::get('/pengaduan', [ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
Route::get('/complaints/success/{trackingCode}', [ComplaintController::class, 'success'])->name('complaints.success');
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
Route::get('/tracking/{tracking_code}', [TrackingController::class, 'show'])->name('tracking.show');
Route::get('/jadwal-dokter', [TrackingController::class, 'embedLayanan'])
    ->name('public.embed-jadwal-dokter');

/*
|--------------------------------------------------------------------------
| Profile (Semua User Terautentikasi & Aktif)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Area (Grup Utama: Admin, Supervisor, Super Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active', 'role:admin,supervisor,super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard & Notifikasi
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])
            ->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');

        // AI Asistant
        Route::post('/ai-suggestion/complaints/{complaint}', [ComplaintResponseController::class, 'aiSuggestion'])
            ->name('complaints.ai-suggestion');
        Route::post('/complaints/{complaint}/ai-chat', [ComplaintResponseController::class, 'aiChat'])
            ->name('admin.complaints.ai-chat');

        // Manajemen Kategori & Laporan Cetak PDF (SLA)
        Route::resource('categories', ComplaintCategoryController::class)->except(['show', 'create', 'edit']);
        Route::get('/reports/complaints', ComplaintReportController::class)->name('reports.complaints');
        Route::get('reports/rooms', RoomComplaintReportController::class)->name('reports.rooms');

        // Pengaduan (Complaints) - Read & Detail
        Route::resource('complaints', AdminComplaintController::class)->only(['index', 'show']);

        // Respon Pengaduan (Alur Kerja Operasional harian Admin & Supervisor)
        Route::post('/complaints/{complaint}/responses', [ComplaintResponseController::class, 'store'])
            ->name('complaints.responses.store');
        Route::post('/responses/{response}/approve', [ComplaintResponseController::class, 'approve'])
            ->name('responses.approve')->middleware(['role:supervisor,super_admin']);
        Route::post('/responses/{response}/reject', [ComplaintResponseController::class, 'reject'])
            ->name('responses.reject')->middleware(['role:supervisor,super_admin']);
        Route::post('/complaints/{complaint}/reject', [ComplaintResponseController::class, 'rejectComplaint'])
            ->name('complaints.reject')->middleware(['role:supervisor,super_admin']);
        Route::post('/complaints/{complaint}/solve', [ComplaintResponseController::class, 'solve'])
            ->name('complaints.solve');

        /*
        |------------------------------------------------------------------
        | Khusus Supervisor & Super Admin
        |------------------------------------------------------------------
        */
        Route::middleware(['role:supervisor,super_admin'])->group(function () {
            // Melihat jejak perubahan sistem
            Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        });

        /*
        |------------------------------------------------------------------
        | Khusus Super Admin (Akses Mutlak / Tertinggi)
        |------------------------------------------------------------------
        */
        Route::middleware(['role:super_admin'])->group(function () {
            Route::resource('users', UserController::class)->except(['show', 'create', 'edit']);
            Route::resource(
                'rooms',
                RoomController::class
            )
                ->only([
                    'index',
                    'store',
                    'update',
                    'destroy',
                ])
                ->names('rooms');
        });

    });

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';