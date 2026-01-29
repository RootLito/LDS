<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'registerEmployee'])->name('register.submit');

Route::get('/employee-login', [AuthController::class, 'showEmployeeLoginForm'])->name('employee.login.form');
Route::post('/employee-login', [AuthController::class, 'employeeLogin'])->name('employee.login.submit');

Route::get('/admin-login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login.form');
Route::post('/admin-login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/employee-logout', [AuthController::class, 'employeeLogout'])->name('employee.logout');

Route::middleware(['auth:employee'])->group(function () {
    Route::get('/employee/dashboard', [TrainingController::class, 'dashboard'])->name('employee.dashboard');
    Route::post('/employee/training-attended', [TrainingController::class, 'storeAttendedTraining'])->name('training.attended.store');
    Route::put('/employee/training-attended/{id}', [TrainingController::class, 'updateAttendedTraining'])->name('training.attended.update');
    Route::delete('/employee/training-attended/{id}', [TrainingController::class, 'destroyAttendedTraining'])->name('training.attended.destroy');
    Route::get('/employee/profile', [EmployeeController::class, 'profile'])->name('employee.profile');
    Route::post('/employee/profile-picture', [EmployeeController::class, 'updateProfilePicture'])->name('employee.profile.updateProfile');
    Route::post('/employee/profile', [EmployeeController::class, 'updateAccount'])->name('employee.profile.update');
    Route::get('/employee/certificates', [TrainingController::class, 'certificates'])->name('employee.certificates');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [TrainingController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/trainings', [TrainingController::class, 'trainings'])->name('admin.trainings');
    Route::get('/admin/trainings/create', [TrainingController::class, 'create'])->name('trainings.create');
    Route::post('/admin/trainings', [TrainingController::class, 'store'])->name('trainings.store');
    Route::get('/admin/trainings/{id}/edit', [TrainingController::class, 'edit'])->name('admin.trainings.edit');
    Route::put('/admin/trainings/{id}', [TrainingController::class, 'update'])->name('admin.trainings.update');
    Route::delete('/admin/trainings/{id}', [TrainingController::class, 'destroy'])->name('admin.trainings.destroy');
    Route::get('/admin/employees', [TrainingController::class, 'employees'])->name('admin.employee');
    Route::get('/admin/employees/{id}', [TrainingController::class, 'show'])->name('admin.employee.profile');
    Route::get('/export-trainings', [TrainingController::class, 'exportTrainings'])->name('export.trainings');
    Route::get('/admin/certificates', [TrainingController::class, 'allCertificates'])->name('admin.certificates');
    Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
    Route::get('/skills/create', [SkillController::class, 'create'])->name('skills.create');
    Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
    Route::get('/skills/{skill}/edit', [SkillController::class, 'edit'])->name('skills.edit');
    Route::put('/skills/{skill}', [SkillController::class, 'update'])->name('skills.update');
    Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');
});
