<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ResourceController;

Route::get('/', fn () => response()->json([
    'service' => 'gam3a-backend',
    'status' => 'ok',
    'message' => 'Gam3a API is running. Open /api/health or use the Next.js app for presentation.',
    'architecture' => [
        'content' => 'WordPress',
        'business_users' => 'Laravel/PostgreSQL',
        'files' => 'S3',
        'presentation' => 'Next.js',
        'operations' => 'Admin dashboard',
    ],
]));

Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    });

    Route::middleware(['auth', 'can:operate-platform'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/applications', [ApplicationController::class, 'index'])->name('admin.applications.index');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('admin.applications.show');
        Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('admin.applications.status');
        Route::get('/applications/{application}/documents/{document}', [ApplicationController::class, 'download']);
        Route::patch('/applications/{application}/documents/{document}', [ApplicationController::class, 'reviewDocument']);

        Route::get('/resources/{resource}', [ResourceController::class, 'index'])->name('admin.resources.index');
        Route::get('/resources/{resource}/create', [ResourceController::class, 'create'])->name('admin.resources.create');
        Route::post('/resources/{resource}', [ResourceController::class, 'store'])->name('admin.resources.store');
        Route::get('/resources/{resource}/{id}/edit', [ResourceController::class, 'edit'])->name('admin.resources.edit');
        Route::patch('/resources/{resource}/{id}', [ResourceController::class, 'update'])->name('admin.resources.update');
        Route::delete('/resources/{resource}/{id}', [ResourceController::class, 'destroy'])->name('admin.resources.destroy');
    });
});
