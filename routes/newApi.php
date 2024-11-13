<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseCreatorRequestController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\PublishRequestController;
use App\Http\Controllers\RefundRequestController;

use Illuminate\Support\Facades\Route;
// use Mockery\Generator\StringManipulation\Pass\Pass;

Route::prefix('auth')->group(function () {
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::get('reset-password/{token}', [AuthController::class, 'sendResetPasswordForm'])->name('secure.route');
    Route::post('reset-password/{token}', [AuthController::class, 'resetPassword']);
});
Route::prefix('auth')->middleware(['jwt.auth', 'role:admin,user'])->group(function () {
    Route::put('reset-password-request', [PasswordResetRequestController::class, 'update']);
    Route::post('reset-authorized-requests', [PasswordResetRequestController::class, 'resetApprovedRequests']);
    Route::apiResource('/reset-password-request', PasswordResetRequestController::class);
    Route::apiResource('create-course', CourseCreatorRequestController::class);
    Route::put('refund-request/change-state', [RefundRequestController::class, 'update']);
    Route::apiResource('refund-request', RefundRequestController::class);
    Route::put('/change-creator-state', [CourseCreatorRequestController::class, 'changeState']);
});

Route::prefix('learning')->middleware(['jwt.auth', 'role:admin,user'])->group(function () {
    Route::put('/publish-requests', [PublishRequestController::class, 'update']);
    Route::get('/rejected-publish-remark/{course_id}', [PublishRequestController::class, 'getRejectedPublishRemark']);
    Route::apiResource('/publish-requests', PublishRequestController::class);
});
