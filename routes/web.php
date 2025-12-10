<?php

use App\Http\Controllers\Announcements\AnnouncementController;
use App\Http\Controllers\api\DependentDropdownController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\StudentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;







Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->prefix('groups')->name('groups.')->group(function () {
    Route::get('/', [GroupController::class, 'grupIndex'])->name('index');
    Route::get('/create', [GroupController::class, 'grupCreate'])->name('create');
    Route::post('/', [GroupController::class, 'grupStore'])->name('store');
    Route::get('/{id}', [GroupController::class, 'grupShow'])->name('show');
    Route::get('/{id}/edit', [GroupController::class, 'grupEdit'])->name('edit');
    Route::put('/{id}', [GroupController::class, 'grupUpdate'])->name('update');
    Route::delete('/{id}', [GroupController::class, 'grupDestroy'])->name('destroy');
});
Route::middleware(['auth', 'verified'])->group(function () {

   Route::get('/api/universities/{city}', [DependentDropdownController::class, 'universities']);
    Route::get('/api/faculties/{university}', [DependentDropdownController::class, 'faculties']);
    Route::get('/api/departments/{faculty}', [DependentDropdownController::class, 'departments']);
    Route::get('/api/classes/{department}', [DependentDropdownController::class, 'classes']);

});

Route::middleware(['auth'])->prefix('groups/{group}/students')->name('students.')->group(function () {
    Route::get('/', [StudentController::class, 'studentIndex'])->name('index');
    Route::get('/create', [StudentController::class, 'studentCreate'])->name('create');
    Route::post('/', [StudentController::class, 'studentStore'])->name('store');
    Route::delete('/{student}', [StudentController::class, 'studentDestroy'])->name('destroy');
});




Route::middleware(['auth', 'verified'])->group(function () {


    Route::resource('announcements', AnnouncementController::class);

});



 Route::get('/students/sample/csv', [StudentController::class, 'downloadSampleCsv'])
        ->name('students.sample.csv');

    Route::get('/students/sample/excel', [StudentController::class, 'downloadSampleExcel'])
        ->name('students.sample.excel');

require __DIR__.'/auth.php';
