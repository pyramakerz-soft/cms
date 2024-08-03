<?php

use App\Http\Controllers\Dashboard\ClassController;
use App\Http\Controllers\Dashboard\CourseController;
use App\Http\Controllers\Dashboard\InstructorController;
use App\Http\Controllers\Dashboard\ProgramController;
use App\Http\Controllers\Dashboard\SchoolController;
use App\Http\Controllers\Dashboard\StageController;
use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\ReportController;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    });
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/completionReport', [ReportController::class, 'completionReport'])->name('reports.completionReport');
    Route::get('/reports/masteryReport', [ReportController::class, 'masteryReport'])->name('reports.masteryReport');
    Route::get('/reports/numOfTrialsReport', [ReportController::class, 'numOfTrialsReport'])->name('reports.numOfTrialsReport');
    Route::get('/reports/skillReport', [ReportController::class, 'skillReport'])->name('reports.skillReport');


    Route::get('/get-courses/{id}', [StudentController::class, 'getCourses']);

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
});


Route::post('/logout', function () {
    Auth::logout();
    return redirect('/'); // Redirect to the homepage or login page
})->name('logout');
