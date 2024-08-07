<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Dashboard\ClassController;
use App\Http\Controllers\Dashboard\CourseController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\InstructorController;
use App\Http\Controllers\Dashboard\ProgramController;
use App\Http\Controllers\Dashboard\SchoolController;
use App\Http\Controllers\Dashboard\StageController;
use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::middleware('guest')->group(function () {
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::get('register', [RegisteredUserController::class, 'create'])
    ->name('register');

Route::post('register', [RegisteredUserController::class, 'store']);



Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');
// });

Route::middleware('auth')->group(function () {
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


    Route::get("/", [DashboardController::class, 'index'])->name("dashboard");
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/completionReport', [ReportController::class, 'completionReport'])->name('reports.completionReport');
    Route::get('/reports/masteryReport', [ReportController::class, 'masteryReport'])->name('reports.masteryReport');
    Route::get('/reports/numOfTrialsReport', [ReportController::class, 'numOfTrialsReport'])->name('reports.numOfTrialsReport');
    Route::get('/reports/skillReport', [ReportController::class, 'skillReport'])->name('reports.skillReport');
});


//////////////////////////// Start class reports//////////////////////////////
Route::get('/select-group', [ReportController::class, 'selectGroup'])->name('select.group');
Route::get('/class-completion-report', [ReportController::class, 'classCompletionReportWeb'])->name('class.completion.report.web');
Route::get('/class-mastery-report', [ReportController::class, 'classMasteryReportWeb'])->name('class.mastery.report.web');
Route::get('/class-num-of-trials-report', [ReportController::class, 'classNumOfTrialsReportWeb'])->name('class.num.of.trials.report.web');
//////////////////////////// End class reports//////////////////////////////

Route::resource('roles', RoleController::class);
Route::get('/get-courses/{id}', [StudentController::class, 'getCourses']);
Route::get('/get-groups/{program_id}', [InstructorController::class, 'getGroups'])->name('getGroups');
Route::group(['prefix' => 'dashboard'], function () {
    Route::resource('students', StudentController::class);
    Route::post('/import-users', [StudentController::class, 'import'])->name('import.users');

    Route::resource('instructors', InstructorController::class);
    Route::resource('schools', SchoolController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('stages', StageController::class);
    Route::resource('classes', ClassController::class);
    Route::resource('programs', ProgramController::class);
});


Route::post('/logout', function () {
    Auth::logout();
    return redirect('/'); // Redirect to the homepage or login page
})->name('logout');