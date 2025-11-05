<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\MasterScheduleController as AdminMasterScheduleController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\SubscriptionController as ClientSubscriptionController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ScheduleController as TeacherScheduleController;
use App\Http\Controllers\Teacher\SessionLogController;
use App\Http\Controllers\Admin\SessionVerificationController;
use App\Http\Controllers\Admin\DisputeController as AdminDisputeController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\TeacherClientController as AdminTeacherClientController;
use App\Http\Controllers\Teacher\AppointmentController as TeacherAppointmentController;
use App\Http\Controllers\Admin\WeeklySlotController; 

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//======================================================================
// Public Guest Routes
//======================================================================
Route::get('/', function () { return view('welcome'); })->name('welcome');
Route::get('/teachers', function () { return view('teachers'); })->name('teachers.index');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

//======================================================================
// Authenticated Client Routes
//======================================================================
Route::middleware(['auth', 'verified', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/progress-reports', [ProgressReportController::class, 'index'])->name('progress-reports.index');
    Route::get('/my-subscription', [ClientSubscriptionController::class, 'index'])->name('subscription.index');
});

//======================================================================
// Authenticated Teacher Routes
//======================================================================
Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/schedule', [TeacherScheduleController::class, 'index'])->name('schedule.index');
    
    // NEW APPOINTMENT BOOKING ROUTES FOR TEACHERS
    // Route::get('/appointments/create', [TeacherAppointmentController::class, 'create'])->name('appointments.create');
    // Route::post('/appointments', [TeacherAppointmentController::class, 'store'])->name('appointments.store');

    // Session Logging Routes
    Route::get('/sessions/{appointment}/log', [SessionLogController::class, 'create'])->name('sessions.log.create');
    Route::post('/sessions/{appointment}/log', [SessionLogController::class, 'store'])->name('sessions.log.store');
});

//======================================================================
// Authenticated Admin Routes
//======================================================================
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Client Management
    Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [AdminClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [AdminClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}/edit', [AdminClientController::class, 'edit'])->name('clients.edit');
    Route::patch('/clients/{client}', [AdminClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [AdminClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/clients/{client}/subscriptions/create', [AdminSubscriptionController::class, 'create'])->name('clients.subscriptions.create');
    Route::post('/clients/{client}/subscriptions', [AdminSubscriptionController::class, 'store'])->name('clients.subscriptions.store');
    Route::delete('/subscriptions/{subscription}', [AdminSubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    // Teacher Management
    Route::get('/teachers', [AdminTeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [AdminTeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [AdminTeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}/edit', [AdminTeacherController::class, 'edit'])->name('teachers.edit');
    Route::patch('/teachers/{teacher}', [AdminTeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [AdminTeacherController::class, 'destroy'])->name('teachers.destroy');
    Route::post('/teachers/{teacher}/clients', [AdminTeacherClientController::class, 'sync'])->name('teachers.clients.sync');
    Route::post('/teachers/{teacher}/clients/toggle', [AdminTeacherClientController::class, 'toggle'])->name('teachers.clients.toggle');

    // Schedule Management
    // Route::get('/schedule', [AdminMasterScheduleController::class, 'index'])->name('schedule.index');
    // Route::get('/appointments/create', [AdminAppointmentController::class, 'create'])->name('appointments.create');
    // Route::post('/appointments', [AdminAppointmentController::class, 'store'])->name('appointments.store');
    // --- ** NEW WEEKLY ROSTER ROUTES (ADD THESE) ** ---
    Route::get('/roster', [WeeklySlotController::class, 'index'])->name('admin.roster.index');
    Route::post('/roster', [WeeklySlotController::class, 'store'])->name('admin.roster.store');
    Route::delete('/roster/{weeklySlot}', [WeeklySlotController::class, 'destroy'])->name('admin.roster.destroy');

    // Session & Dispute Management
    Route::get('/sessions/verify', [SessionVerificationController::class, 'index'])->name('sessions.verify.index');
    Route::patch('/sessions/{appointment}/verify', [SessionVerificationController::class, 'verify'])->name('sessions.verify');
    Route::post('/sessions/{appointment}/dispute', [AdminDisputeController::class, 'store'])->name('sessions.dispute');
    Route::get('/disputes', [AdminDisputeController::class, 'index'])->name('disputes.index');
    Route::patch('/disputes/{dispute}/resolve', [AdminDisputeController::class, 'resolve'])->name('disputes.resolve');
    Route::patch('/disputes/{dispute}/cancel', [AdminDisputeController::class, 'cancel'])->name('disputes.cancel');
});

//======================================================================
// General Authenticated Routes
//======================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//======================================================================
// Authentication Routes (Breeze)
//======================================================================
require __DIR__.'/auth.php';
