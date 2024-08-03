<?php

use App\Http\Controllers\Dashboard\ClassController;
use App\Http\Controllers\Dashboard\CourseController;
use App\Http\Controllers\Dashboard\InstructorController;
use App\Http\Controllers\Dashboard\ProgramController;
use App\Http\Controllers\Dashboard\SchoolController;
use App\Http\Controllers\Dashboard\StageController;
use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Dashboard\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('dashboard.index');
});
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/completionReport', [ReportController::class, 'completionReport'])->name('reports.completionReport');
Route::get('/reports/masteryReport', [ReportController::class, 'masteryReport'])->name('reports.masteryReport');
Route::get('/reports/numOfTrialsReport', [ReportController::class, 'numOfTrialsReport'])->name('reports.numOfTrialsReport');
Route::get('/reports/skillReport', [ReportController::class, 'skillReport'])->name('reports.skillReport');


Route::get('/get-courses/{id}', [StudentController::class,'getCourses']);

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