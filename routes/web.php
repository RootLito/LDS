<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;

// --------------------
// Home / Landing
// --------------------
Route::get('/', function () {
    return view('index');
});

// --------------------
// Employee Registration
// --------------------
Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('register.form');

Route::post('/register', [AuthController::class, 'registerEmployee'])
    ->name('register.submit');

// --------------------
// Employee Login
// --------------------
Route::get('/employee-login', [AuthController::class, 'showEmployeeLoginForm'])
    ->name('employee.login.form');

Route::post('/employee-login', [AuthController::class, 'employeeLogin'])
    ->name('employee.login.submit');

// --------------------
// Admin Login
// --------------------
Route::get('/admin-login', [AuthController::class, 'showAdminLoginForm'])
    ->name('admin.login.form');

Route::post('/admin-login', [AuthController::class, 'adminLogin'])
    ->name('admin.login.submit');

// --------------------
// Logout
// --------------------
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::post('/employee-logout', [AuthController::class, 'employeeLogout'])
    ->name('employee.logout');

// ====================
// EMPLOYEE ROUTES
// ====================
Route::middleware(['auth:employee'])->group(function () {

    Route::get('/employee/dashboard', [TrainingController::class, 'dashboard'])
        ->name('employee.dashboard');

    // CREATE Attended Training
    Route::post('/employee/training-attended', [TrainingController::class, 'storeAttendedTraining'])
        ->name('training.attended.store');

    // EDIT Attended Training (Modal)
    Route::put('/employee/training-attended/{id}', [TrainingController::class, 'updateAttendedTraining'])
        ->name('training.attended.update');

    // DELETE Attended Training (Modal)
    Route::delete('/employee/training-attended/{id}', [TrainingController::class, 'destroyAttendedTraining'])
        ->name('training.attended.destroy');

    Route::get('/employee/profile', [EmployeeController::class, 'profile'])
        ->name('employee.profile');

    Route::post('/employee/profile', [EmployeeController::class, 'updateProfile'])
        ->name('employee.profile.update');
});


// ====================
// ADMIN ROUTES
// ====================
Route::middleware(['auth:admin'])->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', [TrainingController::class, 'adminDashboard'])
        ->name('admin.dashboard');

    // --------------------
    // Training Management (CRUD)
    // --------------------

    // LIST + FILTER
    Route::get('/admin/trainings', [TrainingController::class, 'trainings'])
        ->name('admin.trainings');

    // CREATE
    Route::get('/admin/trainings/create', [TrainingController::class, 'create'])
        ->name('trainings.create');

    Route::post('/admin/trainings', [TrainingController::class, 'store'])
        ->name('trainings.store');

    // EDIT
    Route::get('/admin/trainings/{id}/edit', [TrainingController::class, 'edit'])
        ->name('admin.trainings.edit');

    // UPDATE
    Route::put('/admin/trainings/{id}', [TrainingController::class, 'update'])
        ->name('admin.trainings.update');

    // DELETE
    Route::delete('/admin/trainings/{id}', [TrainingController::class, 'destroy'])
        ->name('admin.trainings.destroy');

    // --------------------
    // Employee Management
    // --------------------
    Route::get('/admin/employees', [TrainingController::class, 'employees'])
        ->name('admin.employee');
    Route::get('/admin/employees/{id}', [TrainingController::class, 'show'])
        ->name('admin.employee.profile');

    Route::get('/export-trainings', [TrainingController::class, 'exportTrainings'])->name('export.trainings');
});
