<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\SubscriptionController; // Ensure this is imported
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Teacher\ScheduleController as TeacherScheduleController;
use App\Http\Controllers\Teacher\AppointmentController as TeacherAppointmentController;
use App\Http\Controllers\Admin\WeeklySlotController; 
use App\Http\Controllers\Admin\SessionVerificationController;
use App\Http\Controllers\Teacher\SessionLogController;
use App\Http\Controllers\ProgressReportController;
use App\Http\Controllers\Coordinator\DashboardController as CoordinatorDashboardController;
use App\Http\Controllers\Coordinator\ClientController as CoordinatorClientController;
use App\Http\Controllers\Coordinator\TeacherController as CoordinatorTeacherController;
use App\Http\Controllers\Coordinator\SubscriptionController as CoordinatorSubscriptionController; 
use App\Http\Controllers\Coordinator\WeeklySlotController as CoordinatorWeeklySlotController;
use App\Http\Controllers\Coordinator\ClientTeacherController as CoordinatorClientTeacherController;
use App\Http\Controllers\Coordinator\SessionVerificationController as CoordinatorSessionVerificationController;
use App\Http\Controllers\Coordinator\DisputeController as CoordinatorDisputeController;
use App\Http\Controllers\Client\SubscriptionController as ClientSubscriptionController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\Admin\TeacherClientController;
use App\Http\Controllers\PublicTeacherController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\Admin\StudySubjectController;


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

// ... existing code ...
Route::get('/', function () {
    return view('welcome');
});

Route::get('/teachers', [PublicTeacherController::class, 'index'])->name('teachers.index');
Route::get('/teachers/{teacher}', [PublicTeacherController::class, 'show'])->name('teachers.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Inbox Routes
    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/{id}', [InboxController::class, 'show'])->name('inbox.show');
});


// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Clients Management
    Route::resource('clients', ClientController::class);
    // Add these routes for client subscription management by admin
    Route::get('clients/{client}/subscription/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('clients/{client}/subscription', [SubscriptionController::class, 'store'])->name('subscriptions.store'); // This was missing
    
    // ... existing code ...
    // Teachers Management
    Route::resource('teachers', TeacherController::class);
    
    // Appointments Management
    Route::resource('appointments', AppointmentController::class);

    // Weekly Slots Management
    Route::get('/weekly-slots', [WeeklySlotController::class, 'index'])->name('weekly-slots.index');
    Route::post('/weekly-slots', [WeeklySlotController::class, 'store'])->name('weekly-slots.store');
    Route::delete('/weekly-slots/{id}', [WeeklySlotController::class, 'destroy'])->name('weekly-slots.destroy');
    // Session Verification
    Route::get('/sessions', [SessionVerificationController::class, 'index'])->name('sessions.index');
    Route::post('/sessions/{id}/verify', [SessionVerificationController::class, 'verify'])->name('sessions.verify');
    Route::post('/sessions/{id}/reject', [SessionVerificationController::class, 'reject'])->name('sessions.reject'); // Ensure reject is POST/PUT

    // Dispute Management
    Route::resource('disputes', App\Http\Controllers\Admin\DisputeController::class);
     Route::post('/disputes/{dispute}/resolve', [App\Http\Controllers\Admin\DisputeController::class, 'resolve'])->name('disputes.resolve');

    // Coordinators Management
    Route::resource('coordinators', CoordinatorController::class);

    // Activity Logs
    Route::get('/activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Messages (Contact Form)
    Route::resource('messages', MessageController::class)->only(['index', 'show', 'create', 'store']);
    Route::post('messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');

    // Teacher-Client Management
    Route::get('teacher-client', [TeacherClientController::class, 'index'])->name('teacher-client.index');
    Route::post('teacher-client/assign', [TeacherClientController::class, 'assign'])->name('teacher-client.assign');
    Route::delete('teacher-client/detach', [TeacherClientController::class, 'detach'])->name('teacher-client.detach');
    
     // Study Subjects Routes
    Route::resource('study-subjects', StudySubjectController::class);
});

// Teacher Routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    
    // Schedule Management
    Route::get('/schedule', [TeacherScheduleController::class, 'index'])->name('schedule.index');
    Route::post('/schedule', [TeacherScheduleController::class, 'store'])->name('schedule.store');
    Route::get('/schedule/{id}/edit', [TeacherScheduleController::class, 'edit'])->name('schedule.edit'); // Add edit route
    Route::put('/schedule/{id}', [TeacherScheduleController::class, 'update'])->name('schedule.update'); // Add update route
    Route::delete('/schedule/{id}', [TeacherScheduleController::class, 'destroy'])->name('schedule.destroy');
    
    // Appointments
    Route::resource('appointments', TeacherAppointmentController::class);
    
    // Session Logs
     Route::get('/sessions/log', [SessionLogController::class, 'create'])->name('sessions.log.create');
    Route::post('/sessions/log', [SessionLogController::class, 'store'])->name('sessions.log.store');
    Route::get('/sessions/history', [App\Http\Controllers\Teacher\LessonHistoryController::class, 'index'])->name('sessions.history');

    // Profile show/edit for public view logic can be handled by standard ProfileController or dedicated method
    Route::get('/profile-preview', [PublicTeacherController::class, 'preview'])->name('profile.preview');
});

// Coordinator Routes
Route::middleware(['auth', 'role:coordinator'])->prefix('coordinator')->name('coordinator.')->group(function () {
    Route::get('/dashboard', [CoordinatorDashboardController::class, 'index'])->name('dashboard');
    
    // Coordinator Client Management
    Route::resource('clients', CoordinatorClientController::class);
    
    // Coordinator Subscription Management
    Route::get('clients/{client}/subscription/create', [CoordinatorSubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('clients/{client}/subscription', [CoordinatorSubscriptionController::class, 'store'])->name('subscriptions.store');

     // Coordinator Teacher Management
    Route::resource('teachers', CoordinatorTeacherController::class);
    Route::get('teachers/{teacher}/schedule', [CoordinatorWeeklySlotController::class, 'index'])->name('teachers.schedule');


    // Client-Teacher Assignment
    Route::get('assign', [CoordinatorClientTeacherController::class, 'index'])->name('assign.index');
    Route::post('assign', [CoordinatorClientTeacherController::class, 'store'])->name('assign.store');
    Route::delete('assign/{id}', [CoordinatorClientTeacherController::class, 'destroy'])->name('assign.destroy'); // Add destroy route
    Route::get('clients/{client}/teachers/edit', [CoordinatorClientTeacherController::class, 'edit'])->name('clients.teachers.edit');
    Route::put('clients/{client}/teachers', [CoordinatorClientTeacherController::class, 'update'])->name('clients.teachers.update');


    // Coordinator Session Verification
    Route::get('/sessions', [CoordinatorSessionVerificationController::class, 'index'])->name('sessions.index');
    Route::post('/sessions/{id}/verify', [CoordinatorSessionVerificationController::class, 'verify'])->name('sessions.verify');
    Route::post('/sessions/{id}/reject', [CoordinatorSessionVerificationController::class, 'reject'])->name('sessions.reject');

    // Coordinator Dispute Management
     Route::resource('disputes', CoordinatorDisputeController::class);
      Route::post('/disputes/{dispute}/resolve', [CoordinatorDisputeController::class, 'resolve'])->name('disputes.resolve');

       // Coordinator Roster
       Route::get('/roster', [CoordinatorWeeklySlotController::class, 'roster'])->name('roster.index');
       Route::get('/roster/{id}/edit', [CoordinatorWeeklySlotController::class, 'editRoster'])->name('roster.edit'); // Add edit route
       Route::put('/roster/{id}', [CoordinatorWeeklySlotController::class, 'updateRoster'])->name('roster.update'); // Add update route

});

// Client/Student Routes
Route::middleware(['auth', 'role:student'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    
    // Subscription
    Route::get('/subscription', [ClientSubscriptionController::class, 'index'])->name('subscription.index'); // Corrected
    Route::get('/subscription/create', [ClientSubscriptionController::class, 'create'])->name('subscription.create');
    Route::post('/subscription', [ClientSubscriptionController::class, 'store'])->name('subscription.store');
    
    // Schedule
     Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

    // Progress Reports
    Route::get('/progress-reports', [ProgressReportController::class, 'index'])->name('progress-reports.index');
    Route::get('/progress-reports/{id}', [ProgressReportController::class, 'show'])->name('progress-reports.show');
});


// Public Pages
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Legal Pages
Route::get('/privacy-policy', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/terms-of-service', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/refund-policy', [LegalController::class, 'refund'])->name('legal.refund');
Route::get('/contact-us', [LegalController::class, 'contact'])->name('legal.contact');


require __DIR__.'/auth.php';