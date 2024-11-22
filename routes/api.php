<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
// use App\Http\Controllers\CredentialController;
use App\Http\Controllers\LearnerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseEnrollmentController;
use App\Http\Controllers\LearnerProgressController;

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AssessmentAttemptController;
use App\Http\Controllers\AnswerLogController;
use App\Http\Controllers\CertificateRequestController;
use App\Http\Controllers\ChatMessagesController;
use App\Http\Controllers\CourseDiscussionController;
use App\Http\Controllers\ExamRequestController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\CourseCreatorRequestController;
use App\Http\Controllers\CVTemplateController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\LoggedinDevicesController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\PollChoiceController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\PublishRequestController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RefundRequestController;
use App\Http\Controllers\TopicController;
use App\Models\PublishRequest;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Route;
// use Mockery\Generator\StringManipulation\Pass\Pass;

Route::prefix('auth')->group(function () {
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);



    Route::post('/secure-link', function () {
        $url = URL::temporarySignedRoute('secure.route', now()->addMinutes(1), ['user' => 1]);
        return $url;
    });
    Route::get('reset-password/{token}', [AuthController::class, 'sendResetPasswordForm'])->name('secure.route');
    Route::post('reset-password/{token}', [AuthController::class, 'resetPassword']);
    Route::apiResource('cv', CVTemplateController::class);
    Route::get('/cv-file/{filename}', [CVTemplateController::class, 'getTemplateFile']);


    Route::apiResource('/signatures', SignatureController::class);
    Route::get('/my-devices/{id}', [LoggedinDevicesController::class, 'getMyDevices']);
    Route::get('/profile-pic/{filename}', [ProfileController::class, 'getProfilePic']);
    Route::get('/post-file/{filename}', [CourseDiscussionController::class, 'getPostFile']);
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
    Route::put('reset-password-request', [PasswordResetRequestController::class, 'update']);
    Route::post('reset-authorized-requests', [PasswordResetRequestController::class, 'resetApprovedRequests']);
    Route::apiResource('/reset-password-request', PasswordResetRequestController::class);

    Route::apiResource('create-course', CourseCreatorRequestController::class);

    Route::put('refund-request/change-state', [RefundRequestController::class, 'update']);
    Route::apiResource('refund-request', RefundRequestController::class);
    Route::put('/change-creator-state', [CourseCreatorRequestController::class, 'changeState']);
    Route::get('/sign/{filename}', [AuthController::class, 'getFile']);
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
    Route::put('/publish-requests', [PublishRequestController::class, 'update']);
    Route::get('/rejected-publish-remark/{course_id}', [PublishRequestController::class, 'getRejectedPublishRemark']);
    Route::apiResource('/publish-requests', PublishRequestController::class);
    Route::apiResource('exam-requests', ExamRequestController::class);
    Route::put('exam-requests', [ExamRequestController::class, 'update']);
    Route::put('undo-rejected-exam-requests', [ExamRequestController::class, 'undoReject']);
    Route::get('/final-exam/questions/{courseID}', [AssessmentAttemptController::class, 'getFinalExamQuestions']);
    Route::post('/final-exam', [AssessmentAttemptController::class, 'storeFinalExam']);

    Route::post('courses/{course_id}/discussions/{id}', [CourseDiscussionController::class, 'update']);
    Route::apiResource('courses/{course_id}/discussions', CourseDiscussionController::class);
    Route::apiResource('assessement-attempts', AssessmentAttemptController::class);

    Route::apiResource('courses/certificate-requests', CertificateRequestController::class);
    Route::put('/courses/certificate-requests', [CertificateRequestController::class, 'update']);
    Route::put('courses/undo-rejected-certificate-requests', [CertificateRequestController::class, 'undoReject']);
    Route::post('courses/group-certificate-requests', [CertificateRequestController::class, 'groupRequest']);

    Route::apiResource('topics', TopicController::class);
    Route::apiResource('questions', QuestionController::class);
    Route::apiResource('choices', ChoiceController::class);
    Route::apiResource('modules', ModuleController::class);
});

Route::prefix('social')->middleware(['jwt.auth', 'role:admin,user'])->group(function () {
    Route::apiResource('/letters', LetterController::class);
    Route::get('/my-activity', [ActivityLogController::class, 'getMyActivity']);
    Route::apiResource('/chats', ChatMessagesController::class);
    Route::apiResource('/polls', PollController::class);
    Route::get('/get-chosers/{choiceId}', [PollController::class, 'getPollChosers']);
    Route::put('/choose-poll/{id}', [PollChoiceController::class, 'update']);
    Route::put('choices/{choice_id}', [PollChoiceController::class, 'updateChoice']);
    Route::post('choices/{poll_id}', [PollChoiceController::class, 'addChoice']);
    Route::apiResource('/ratings', RatingController::class);
});
