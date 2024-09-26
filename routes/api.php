<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
// use App\Http\Controllers\CredentialController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\LearnerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseEnrollmentController;
use App\Http\Controllers\LearnerProgressController;
use App\Http\Controllers\AssessmentAttemptController;
use App\Http\Controllers\AnswerLogController;
use App\Http\Controllers\CertificateRequestController;
use App\Http\Controllers\ChatMessagesController;
use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\CourseDiscussionController;
use App\Http\Controllers\ExamRequestController;
use App\Http\Controllers\LoggedinDevicesController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\TopicController;
use App\Models\CertificateRequest;
// use App\Models\Subscription;
use Illuminate\Support\Facades\Route;
// use App\Http\Middleware\CheckRole;

Route::prefix('auth')->group(function () {
    Route::apiResource('/signatures', SignatureController::class);
    Route::get('/my-devices/{id}', [LoggedinDevicesController::class, 'getMyDevices']);
    Route::get('/profile-pic/{filename}', [ProfileController::class, 'getProfilePic']);
    Route::get('/post-file/{filename}', [CourseDiscussionController::class, 'getPostFile']);
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
    Route::apiResource('profiles', ProfileController::class)->only(['show', 'update']);
    Route::apiResource('identities', IdentityController::class);
    Route::apiResource('address', AddressController::class);

    Route::apiResource('courses', CourseController::class);
    Route::apiResource('enrollments', CourseEnrollmentController::class);
    Route::get('courses/{course}/enrollments', [CourseEnrollmentController::class, 'index']);
    Route::get('/get-my-enrollments', [CourseEnrollmentController::class, 'getMyEnrollments']);
    Route::apiResource('learner-progress', LearnerProgressController::class);
    Route::apiResource('ans-logs', AnswerLogController::class);
});
Route::prefix('auth')->middleware(['jwt.auth', 'role:admin,user'])->group(function () {
    Route::apiResource('subs', SubscriptionController::class);
    Route::post('/invite-learner', [SubscriptionController::class, 'addLearnersToMySubscription']);
    Route::get('/invited-learners/{id}', [SubscriptionController::class, 'getInvitedLearners']);
    Route::delete('/remove-learners/{id}', [SubscriptionController::class, 'removeLearners']);
    Route::apiResource('learners', LearnerController::class);
    Route::get('my-subscriptions', [SubscriptionController::class, 'getMySubscriptions']);
    Route::delete('users/{user}', [AuthController::class, 'destroy'])->name('users.destroy');
    Route::apiResource('users', AuthController::class)->except(['destroy']);
    Route::apiResource('products', ProductController::class);
});

Route::prefix('learning')->middleware(['jwt.auth', 'role:admin,user'])->group(function () {
    Route::apiResource('exam-requests', ExamRequestController::class);
    Route::put('exam-requests/approve/{id}', [ExamRequestController::class, 'approveExamRequest']);
    Route::put('exam-requests/authorize/{id}', [ExamRequestController::class, 'authorizeExamRequest']);
    Route::apiResource('courses/{course_id}/discussions', CourseDiscussionController::class);
    Route::apiResource('assessement-attempts', AssessmentAttemptController::class);
    Route::get('comments/{id}', [CourseDiscussionController::class, 'getPostChildren']);
    Route::apiResource('topics', TopicController::class);
    Route::apiResource('questions', QuestionController::class);
    Route::apiResource('choices', ChoiceController::class);
    Route::apiResource('modules', ModuleController::class);
    Route::apiResource('courses/{course_id}/certificate-requests', CertificateRequestController::class);
    Route::put('courses/{course_id}/approve-certificate-requests', [CertificateRequestController::class, 'approveRequest']);
    Route::put('courses/{course_id}/reject-certificate-requests', [CertificateRequestController::class, 'rejectRequest']);
    Route::put('courses/{course_id}/authorize-certificate-requests', [CertificateRequestController::class, 'authorizeRequest']);
    Route::post('courses/{course_id}/group-certificate-requests', [CertificateRequestController::class, 'groupRequest']);
});

Route::prefix('social')->middleware(['jwt.auth'])->group(function () {
    Route::apiResource('/chats', ChatMessagesController::class);
});
