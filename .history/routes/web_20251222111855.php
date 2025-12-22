<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
// use App\Http\Controllers\Admin\MasterScheduleController as AdminMasterScheduleController; // <-- No longer used
// use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController; // <-- No longer used
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\SubscriptionController as ClientSubscriptionController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ScheduleController as TeacherScheduleController;
use App\Http\Controllers\Teacher\SessionLogController;
use App\Http\Controllers\Admin\SessionVerificationController;
use App\Http\Controllers\Admin\DisputeController as AdminDisputeController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\TeacherClientController as AdminTeacherClientController;
// --- ** ADD THIS NEW CONTROLLER ** ---
use App\Http\Controllers\Admin\WeeklySlotController; 
// --- ** END OF ADD ** ---
use App\Http\Controllers\Teacher\LessonHistoryController;

use App\Http\Controllers\Teacher\AppointmentController as TeacherAppointmentController; // <-- No longer used
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Coordinator\DashboardController as CoordinatorDashboardController;
use App\Http\Controllers\Coordinator\ClientController as CoordinatorClientController;
use App\Http\Controllers\Coordinator\SubscriptionController as CoordinatorSubscriptionController;
use App\Http\Controllers\Coordinator\TeacherController as CoordinatorTeacherController;
use App\Http\Controllers\Coordinator\ClientTeacherController as CoordinatorClientTeacherController;
use App\Http\Controllers\Coordinator\WeeklySlotController as CoordinatorWeeklySlotController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Api\MeetReportController;
use App\Http\Controllers\Coordinator\SessionVerificationController as CoordinatorSessionVerificationController;
use App\Http\Controllers\Coordinator\DisputeController as CoordinatorDisputeController;
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
    Route::post('/schedule', [TeacherScheduleController::class, 'store'])->name('schedule.store');
    Route::delete('/schedule/{weeklySlot}', [TeacherScheduleController::class, 'destroy'])->name('schedule.destroy');

    Route::get('/history', [LessonHistoryController::class, 'index'])->name('history.index');

    // Session Logging Routes
    Route::get('/sessions/{weeklySlot}/log', [SessionLogController::class, 'create'])->name('sessions.log.create');
    Route::post('/sessions/{weeklySlot}/log', [SessionLogController::class, 'store'])->name('sessions.log.store');
    Route::get('/schedule/{weeklySlot}/edit', [TeacherScheduleController::class, 'edit'])->name('schedule.edit');
    Route::patch('/schedule/{weeklySlot}', [TeacherScheduleController::class, 'update'])->name('schedule.update');

        // Inbox
    Route:: get('/inbox', [\App\Http\Controllers\InboxController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/{message}', [\App\Http\Controllers\InboxController:: class, 'show'])->name('inbox.show');
    Route::patch('/inbox/{message}/read', [\App\Http\Controllers\InboxController::class, 'markAsRead'])->name('inbox.markAsRead');
    Route::post('/inbox/mark-all-read', [\App\Http\Controllers\InboxController::class, 'markAllAsRead'])->name('inbox.markAllAsRead');

});

//======================================================================
// Authenticated Admin Routes
//======================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
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

    // --- ** THIS IS THE FIX ** ---
    // The `admin.` prefix is removed from the `name()` method,
    // because the group already provides it.
    Route::get('/roster', [WeeklySlotController::class, 'index'])->name('roster.index');
    Route::post('/roster', [WeeklySlotController::class, 'store'])->name('roster.store');
    Route::delete('/roster/{weeklySlot}', [WeeklySlotController::class, 'destroy'])->name('roster.destroy');
    Route::get('/roster/{weeklySlot}/edit', [WeeklySlotController::class, 'edit'])->name('roster.edit');
    Route::patch('/roster/{weeklySlot}', [WeeklySlotController::class, 'update'])->name('roster.update');

    // --- ** END OF FIX ** ---

    // Session & Dispute Management
    Route::get('/sessions/verify', [SessionVerificationController::class, 'index'])->name('sessions.verify.index');
    Route::patch('/sessions/{appointment}/verify', [SessionVerificationController::class, 'verify'])->name('sessions.verify');
    Route::post('/sessions/{appointment}/dispute', [AdminDisputeController::class, 'store'])->name('sessions.dispute');
    Route::get('/disputes', [AdminDisputeController::class, 'index'])->name('disputes.index');
    Route::patch('/disputes/{dispute}/resolve', [AdminDisputeController::class, 'resolve'])->name('disputes.resolve');
    Route::patch('/disputes/{dispute}/cancel', [AdminDisputeController::class, 'cancel'])->name('disputes.cancel');
    Route::post('/sessions/{appointment}/cancel', [SessionVerificationController::class, 'cancel'])->name('sessions.cancel');

    // --- ** ابدأ الإضافة من هنا ** ---
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    // --- ** انتهت الإضافة ** ---
    // Coordinator Management (إدارة المنسقين)
    Route::get('/coordinators', [\App\Http\Controllers\Admin\CoordinatorController::class, 'index'])->name('coordinators.index');
    Route::get('/coordinators/create', [\App\Http\Controllers\Admin\CoordinatorController::class, 'create'])->name('coordinators.create');
    Route::post('/coordinators', [\App\Http\Controllers\Admin\CoordinatorController::class, 'store'])->name('coordinators.store');
    Route::delete('/coordinators/{coordinator}', [\App\Http\Controllers\Admin\CoordinatorController::class, 'destroy'])->name('coordinators.destroy');
    // --- ** انتهت الإضافة ** ---

    // Session & Dispute Management
    Route::get('/sessions/verify', [SessionVerificationController::class, 'index'])->name('sessions.verify.index');
        // Study Subjects Management
    Route::get('/study-subjects', [\App\Http\Controllers\Admin\StudySubjectController::class, 'index'])->name('study-subjects.index');
    Route::get('/study-subjects/create', [\App\Http\Controllers\Admin\StudySubjectController::class, 'create'])->name('study-subjects.create');
    Route::post('/study-subjects', [\App\Http\Controllers\Admin\StudySubjectController::class, 'store'])->name('study-subjects.store');
    Route::get('/study-subjects/{studySubject}/edit', [\App\Http\Controllers\Admin\StudySubjectController::class, 'edit'])->name('study-subjects.edit');
    Route::patch('/study-subjects/{studySubject}', [\App\Http\Controllers\Admin\StudySubjectController::class, 'update'])->name('study-subjects.update');
    Route::delete('/study-subjects/{studySubject}', [\App\Http\Controllers\Admin\StudySubjectController:: class, 'destroy'])->name('study-subjects.destroy');
        // Messages Management
    Route::get('/messages', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [\App\Http\Controllers\Admin\MessageController:: class, 'create'])->name('messages.create');
    Route::post('/messages', [\App\Http\Controllers\Admin\MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'destroy'])->name('messages.destroy');

});

//======================================================================
// Authenticated Coordinator Routes
//======================================================================
Route::middleware(['auth', 'verified', 'role:coordinator'])->prefix('coordinator')->name('coordinator.')->group(function () {
    Route::get('/dashboard', [CoordinatorDashboardController::class, 'index'])->name('dashboard');

    // Coordinator Client Management
    Route::get('/clients', [CoordinatorClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [CoordinatorClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [CoordinatorClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}/edit', [CoordinatorClientController::class, 'edit'])->name('clients.edit');
    Route::patch('/clients/{client}', [CoordinatorClientController::class, 'update'])->name('clients.update');
    
    Route::delete('/clients/{client}', [CoordinatorClientController::class, 'destroy'])->name('clients.destroy');
    // Coordinator Subscription Management
    Route::get('/clients/{client}/subscriptions/create', [CoordinatorSubscriptionController::class, 'create'])->name('clients.subscriptions.create');
    Route::post('/clients/{client}/subscriptions', [CoordinatorSubscriptionController::class, 'store'])->name('clients.subscriptions.store');
    Route::delete('/subscriptions/{subscription}', [CoordinatorSubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    // Coordinator Teacher Management (ربط العملاء بالمعلمين)
    Route::get('/teachers', [CoordinatorTeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/{teacher}/edit', [CoordinatorTeacherController::class, 'edit'])->name('teachers.edit');
    Route::post('/teachers/{teacher}/clients/toggle', [CoordinatorClientTeacherController::class, 'toggle'])->name('teachers.clients.toggle');

    // Coordinator Roster Management (الجدول الأسبوعي)
    Route::get('/roster', [CoordinatorWeeklySlotController::class, 'index'])->name('roster.index');
    Route::post('/roster', [CoordinatorWeeklySlotController::class, 'store'])->name('roster.store');
    Route::delete('/roster/{weeklySlot}', [CoordinatorWeeklySlotController::class, 'destroy'])->name('roster.destroy');
        Route::get('/roster/{weeklySlot}/edit', [\App\Http\Controllers\Coordinator\WeeklySlotController::class, 'edit'])
            ->name('roster.edit');
        Route::patch('/roster/{weeklySlot}', [\App\Http\Controllers\Coordinator\WeeklySlotController::class, 'update'])
            ->name('roster.update');

    // Coordinator Session & Dispute Management
    Route::get('/sessions/verify', [CoordinatorSessionVerificationController::class, 'index'])->name('sessions.verify.index');
    Route::patch('/sessions/{appointment}/verify', [CoordinatorSessionVerificationController::class, 'verify'])->name('sessions.verify');
    Route::post('/sessions/{appointment}/dispute', [CoordinatorDisputeController::class, 'store'])->name('sessions.dispute');
    Route::get('/disputes', [CoordinatorDisputeController::class, 'index'])->name('disputes.index');
    Route::patch('/disputes/{dispute}/resolve', [CoordinatorDisputeController::class, 'resolve'])->name('disputes.resolve');
    Route::patch('/disputes/{dispute}/cancel', [CoordinatorDisputeController::class, 'cancel'])->name('disputes.cancel');
    Route::post('/sessions/{appointment}/cancel', [CoordinatorSessionVerificationController::class, 'cancel'])->name('sessions.cancel');
    // --- ** انتهت الإضافة ** ---
});

//======================================================================
// General Authenticated Routes
//======================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        // --- ADD THIS ROUTE ---
    // This will handle the token generation for Teachers
    Route::post('/profile/token', [ProfileController::class, 'generateToken'])
         ->middleware('role:teacher')
         ->name('profile.token');
});



//======================================================================
// Authentication Routes (Breeze)
//======================================================================




require __DIR__.'/auth.php';

