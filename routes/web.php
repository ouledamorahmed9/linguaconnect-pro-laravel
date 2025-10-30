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
use App\Http\Controllers\Client\DashboardController as ClientDashboardController; // Import the new Client Controller
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ScheduleController as TeacherScheduleController;
use App\Http\Controllers\Teacher\SessionLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SessionVerificationController; // Import the new controller
use App\Http\Controllers\Client\SubscriptionController as ClientSubscriptionController; // Import the new controller
use App\Http\Controllers\Admin\DisputeController as AdminDisputeController; // Import the new controller
use App\Http\Controllers\Admin\DisputeManagementController as AdminDisputeManagementController; // Import the new controller
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController; // Import the new controller

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//======================================================================
// Public Guest Routes
//======================================================================
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/teachers', function () {
    return view('teachers');
})->name('teachers.index');

Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');


//======================================================================
// Authenticated Client Routes
//======================================================================
Route::middleware(['auth', 'verified', 'role:client'])->prefix('client')->name('client.')->group(function () {
    // THIS IS THE FINAL REFACTOR: Use the new controller
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/my-schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/progress-reports', [ProgressReportController::class, 'index'])->name('progress-reports.index');
        // ADD THIS NEW ROUTE for the client's subscription page
    Route::get('/my-subscription', [ClientSubscriptionController::class, 'index'])->name('subscription.index');

});


//======================================================================
// Authenticated Teacher Routes
//======================================================================
Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/schedule', [TeacherScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/sessions/{appointment}/log', [SessionLogController::class, 'create'])->name('sessions.log.create');
    Route::post('/sessions/{appointment}/log', [SessionLogController::class, 'store'])->name('sessions.log.store');
});


//======================================================================
// Authenticated Admin Routes
//======================================================================
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // CLIENT MANAGEMENT ROUTES
    Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [AdminClientController::class, 'create'])->name('clients.create'); // Shows the form
    Route::post('/clients', [AdminClientController::class, 'store'])->name('clients.store'); // Handles form submission
    Route::get('/clients/{client}/edit', [AdminClientController::class, 'edit'])->name('clients.edit');
    Route::patch('/clients/{client}', [AdminClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [AdminClientController::class, 'destroy'])->name('clients.destroy');


    Route::get('/teachers', [AdminTeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [AdminTeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [AdminTeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}/edit', [AdminTeacherController::class, 'edit'])->name('teachers.edit');
    Route::patch('/teachers/{teacher}', [AdminTeacherController::class, 'update'])->name('teachers.update');
    Route::get('/schedule', [AdminMasterScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/appointments/create', [AdminAppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AdminAppointmentController::class, 'store'])->name('appointments.store');
    Route::delete('/teachers/{teacher}', [AdminTeacherController::class, 'destroy'])->name('teachers.destroy');

    // Subscription Management Routes
    Route::get('/clients/{client}/subscriptions/create', [AdminSubscriptionController::class, 'create'])->name('clients.subscriptions.create');
    // ADD THIS NEW ROUTE for storing the subscription
    Route::post('/clients/{client}/subscriptions', [AdminSubscriptionController::class, 'store'])->name('clients.subscriptions.store');

    // Session Verification Routes
    Route::get('/sessions/verify', [SessionVerificationController::class, 'index'])->name('sessions.verify.index');
    // ADD THIS NEW ROUTE for verifying a session
    Route::patch('/sessions/{appointment}/verify', [SessionVerificationController::class, 'verify'])->name('sessions.verify');
    // ADD THIS NEW ROUTE for Disputing a session
    Route::post('/sessions/{appointment}/dispute', [AdminDisputeController::class, 'store'])->name('sessions.dispute');
    // ADD THIS NEW ROUTE for managing disputes
    Route::get('/disputes', [AdminDisputeController::class, 'index'])->name('disputes.index');

});


//======================================================================
// General Authenticated Routes (Profile, etc.)
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

