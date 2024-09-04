<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\LearnerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseEnrollmentController;
use App\Http\Controllers\LearnerProgressController;
use App\Http\Controllers\AssessmentAttemptController;
use App\Http\Controllers\AnswerLogController;
use App\Http\Controllers\LoggedinDevicesController;
use App\Models\Subscription;
use Illuminate\Support\Facades\Route;
// use App\Http\Middleware\CheckRole;

Route::prefix('auth')->group( function(){
    
    Route::get('/my-devices/{id}', [LoggedinDevicesController::class, 'getMyDevices']);
    Route::get('/profile-pic/{filename}', [ProfileController::class, 'getProfilePic']);
    // Route::post('/register/step/{step}/{id}', [IdentityController::class, 'postStep1']);
    Route::post('/register/step1', [IdentityController::class, 'postStep1']);
    Route::post('/register/step2/{id}', [IdentityController::class, 'postStep2']);
    Route::post('/register/step3/{id}', [IdentityController::class, 'postStep3']);
    Route::post('/register/step4/{id}', [IdentityController::class, 'postStep4']);
    Route::post('/register/step5/{id}', [IdentityController::class, 'postStep5']);

    Route::post('login', [AuthController::class, 'login']);

    Route::post('/logout/{id}', [AuthController::class, 'logout']);
    Route::post('/logout-all/{id}', [AuthController::class, 'logoutFromAllOtherDevices']);

    Route::post('/profile/{id}', [ProfileController::class, 'update']);
    Route::apiResource('profiles', ProfileController::class)->only(['show','update']);
    Route::apiResource('identities', IdentityController::class);
    Route::apiResource('address', AddressController::class);//use api-apiResource instead of apiResource
    Route::apiResource('subs', SubscriptionController::class);
    Route::post('/invite-learner', [SubscriptionController::class, 'addLearnersToMySubscription']);
    Route::get('/invited-learners/{id}', [SubscriptionController::class, 'getInvitedLearners']);
    Route::delete('/remove-learners/{id}', [SubscriptionController::class, 'removeLearners']);
    Route::apiResource('learners', LearnerController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('enrollments', CourseEnrollmentController::class);
    Route::get('/get-my-enrollments', [CourseEnrollmentController::class, 'getMyEnrollments']);
    //enrollments
    Route::apiResource('learner-progress', LearnerProgressController::class);
    Route::apiResource('assessment-attempts', AssessmentAttemptController::class);
    Route::apiResource('ans-logs', AnswerLogController::class);//answer-logs
    // ->middleware(['role:admin']);
});
Route::prefix('auth')->middleware(['jwt.auth','role:admin,user'])->group(function () {
    Route::get('my-subscriptions', [SubscriptionController::class, 'getMySubscriptions']);
    Route::delete('users/{user}', [AuthController::class, 'destroy'])->name('users.destroy');
    Route::apiResource('users', AuthController::class)->except(['destroy']);
    // Route::get('users/{user}')
    Route::apiResource('products', ProductController::class);
});
