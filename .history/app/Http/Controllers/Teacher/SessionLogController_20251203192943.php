<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SessionLogController extends Controller
{
    /**
     * Show the form for creating a new session log for an appointment.
     * Uses the appointment's own clients (group-aware).
     */
    public function create(Appointment $appointment)
    {
        // Security: teacher must own this appointment
        if ($appointment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load all clients attached to this appointment
        $appointment->load('clients', 'client');

        $clients = $appointment->clients;
        if ($clients->isEmpty() && $appointment->client) {
            // Backward compatibility: single client on appointment
            $clients = collect([$appointment->client]);
        }

        if ($clients->isEmpty()) {
            return redirect()->route('teacher.schedule.index')
                ->withErrors(['message' => 'لا يوجد طلاب مرتبطون بهذه الجلسة. الرجاء مراجعة الجدول.']);
        }

        // Ensure each student has an active subscription
        foreach ($clients as $client) {
            if (!method_exists($client, 'hasActiveSubscription') || !$client->hasActiveSubscription()) {
                return redirect()->route('teacher.schedule.index')
                    ->withErrors([
                        'message' => "فشل التسجيل. لا يملك الطالب {$client->name} اشتراكاً نشطاً أو رصيداً كافياً.",
                    ]);
            }
        }

        return view('teacher.sessions.log', [
            'appointment' => $appointment,
            'clients'     => $clients,
        ]);
    }

    /**
     * Store a newly created session log for an appointment.
     * Updates the appointment and keeps all students attached.
     */
    public function store(Request $request, Appointment $appointment)
    {
        // Security: teacher must own this appointment
        if ($appointment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Base validation
        $validated = $request->validate([
            'topic'             => 'required|string|max:255',
            'teacher_notes'     => 'nullable|string',
            'completion_status' => [
                'required',
                'string',
                Rule::in(['completed', 'no_show', 'technical_issue']),
            ],
            'google_meet_link'  => 'nullable|url',
            'extension_data'    => 'nullable|string',
        ]);

        // Load clients again
        $appointment->load('clients', 'client');

        $clients = $appointment->clients;
        if ($clients->isEmpty() && $appointment->client) {
            $clients = collect([$appointment->client]);
        }

        if ($clients->isEmpty()) {
            return redirect()->route('teacher.schedule.index')
                ->withErrors(['message' => 'لا يوجد طلاب مرتبطون بهذه الجلسة.']);
        }

        // Optional: prevent double-logging by status check
        if (in_array($appointment->status, ['logged', 'verified'])) {
            return redirect()->route('teacher.schedule.index')
                ->withErrors(['message' => 'تم تسجيل هذه الحصة سابقاً.']);
        }

        DB::beginTransaction();

        try {
            // Update appointment fields
            $appointment->update([
                'topic'             => $validated['topic'],
                'teacher_notes'     => $validated['teacher_notes'] ?? null,
                'completion_status' => $validated['completion_status'],
                'google_meet_link'  => $validated['google_meet_link'] ?? null,
                'extension_data'    => $validated['extension_data'] ?? null,
                'status'            => 'logged',
                'is_group'          => $clients->count() > 1,
            ]);

            // Update per-student pivot fields
            $pivotData = [];
            foreach ($clients as $client) {
                $pivotData[$client->id] = [
                    'completion_status' => $validated['completion_status'],
                    'notes'             => $validated['teacher_notes'] ?? null,
                ];
            }

            $appointment->clients()->syncWithoutDetaching($pivotData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('teacher.schedule.index')
                ->withErrors([
                    'message' => 'حدث خطأ أثناء تسجيل الحصة: ' . $e->getMessage(),
                ]);
        }

        return redirect()->route('teacher.schedule.index')
            ->with('status', $clients->count() > 1
                ? 'تم تسجيل الحصة بنجاح كحصة مجموعات.'
                : 'تم تسجيل الحصة بنجاح.');
    }
}