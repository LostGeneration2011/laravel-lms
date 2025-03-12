<?php

use App\Http\Controllers\Admin\AboutUsSectionController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\BecomeInstructorSectionController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BrandSectionController;
use App\Http\Controllers\Admin\CertificateBuilderController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ContactSettingController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseContentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseLanguageController;
use App\Http\Controllers\Admin\CourseLevelController;
use App\Http\Controllers\Admin\CourseSubCategoryController;
use App\Http\Controllers\Admin\CustomPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DatabaseClearController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\FeaturedInstructorController;
use App\Http\Controllers\Admin\FooterColumnOneController;
use App\Http\Controllers\Admin\FooterColumnTwoController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\InstructorReqeustController;
use App\Http\Controllers\Admin\InstructorRequestController;
use App\Http\Controllers\Admin\LatestCourseSectionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\PayoutGatewayController;
use App\Http\Controllers\Admin\ProfileUpdateController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TopBarController;
use App\Http\Controllers\Admin\VideoSectionController;
use App\Http\Controllers\Admin\WithdrawRequestController;
use App\Http\Controllers\Frontend\HeroController;
use App\Models\BecomeInstructorSection;
use App\Models\FeaturedInstructor;
use App\Models\Footer;
use App\Models\FooterColumnOne;
use App\Models\LatestCourseSection;
use App\Models\TopBar;
use Illuminate\Support\Facades\Route;


Route::group(["middleware" =>"guest", "prefix" => "admin", "as" => "admin."],function () {

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::group(["middleware" =>"auth:admin","prefix" => "admin","as"=>"admin."],function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /** Instructor Request Routes */
    Route::get('instructor-doc-download/{user}',[InstructorRequestController::class,'download'])->name('instructor-doc-download');
    Route::resource('instructor-requests', InstructorRequestController::class);

     /** Course Languages Routes */
     Route::resource('course-languages', CourseLanguageController::class);

     /** Course Levels Routes */
     Route::resource('course-levels', CourseLevelController::class);

      /** Course Categories Routes */
    Route::resource('course-categories', CourseCategoryController::class);


});

