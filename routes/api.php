<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AdminApplicationController;
use App\Http\Controllers\Api\AdminResourceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CalculatorController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\WordPressContentController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => [
    'service' => 'gam3a-backend',
    'status' => 'ok',
    'message' => 'Gam3a API is running. Use /api/health, /api/universities, or connect the Next.js app as the presentation layer.',
    'architecture' => [
        'content' => 'WordPress',
        'business_users' => 'Laravel/PostgreSQL',
        'files' => 'S3',
        'presentation' => 'Next.js',
        'operations' => 'Admin dashboard',
    ],
]);

Route::get('/health', fn () => ['status' => 'ok', 'service' => 'gam3a-backend']);

Route::post('/auth/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::get('/universities', [CatalogController::class, 'universities']);
Route::get('/universities/{slug}', [CatalogController::class, 'university']);
Route::get('/programs', [CatalogController::class, 'programs']);
Route::get('/certificate-tracks', [CatalogController::class, 'certificateTracks']);
Route::get('/calculator-rules', [CalculatorController::class, 'rules']);
Route::post('/calculate-equivalency', [CalculatorController::class, 'calculate']);
Route::get('/equivalency-centers', [CalculatorController::class, 'equivalencyCenters']);
Route::get('/site', [\App\Http\Controllers\Api\SiteController::class, 'index']);
Route::get('/content/articles', [\App\Http\Controllers\Api\SiteController::class, 'articles']);
Route::get('/content/articles/{slug}', [\App\Http\Controllers\Api\SiteController::class, 'article']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', [DashboardController::class, 'student']);
    Route::apiResource('applications', ApplicationController::class);
    Route::post('/applications/{application}/documents', [DocumentController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'can:operate-platform'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin']);
    Route::get('/applications', [AdminApplicationController::class, 'index']);
    Route::get('/applications/{application}', [AdminApplicationController::class, 'show']);
    Route::patch('/applications/{application}/status', [AdminApplicationController::class, 'updateStatus']);
    Route::patch('/applications/{application}/documents/{document}/status', [AdminApplicationController::class, 'updateDocumentStatus']);

    Route::get('/{resource}', [AdminResourceController::class, 'index']);
    Route::post('/{resource}', [AdminResourceController::class, 'store']);
    Route::get('/{resource}/{id}', [AdminResourceController::class, 'show']);
    Route::put('/{resource}/{id}', [AdminResourceController::class, 'update']);
    Route::patch('/{resource}/{id}', [AdminResourceController::class, 'update']);
    Route::delete('/{resource}/{id}', [AdminResourceController::class, 'destroy']);
});
