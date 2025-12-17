<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\WeeklySlot;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SessionLogController extends Controller
{
    /**
     * Show the form for creating a new session log.
     */
    public function create(WeeklySlot $weeklySlot)
    {
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $weeklySlot->load(['students', 'client', 'teacher']);

        $students = $weeklySlot->students;
        if ($students->isEmpty() && $weeklySlot->client) {
            $students = collect([$weeklySlot->client]);
        }

        $inactive = $students->first(fn($s) => !$s->hasActiveSubscription());
        if ($inactive) {
            return redirect()->route('teacher.schedule.index')
                             ->withErrors(['message' => 'لا يمكن تسجيل حصة. أحد الطلاب لا يملك اشتراكاً نشطاً أو رصيداً كافياً.']);
        }

        return view('teacher.sessions.log', [
            'weeklySlot' => $weeklySlot,
            'students'   => $students,
        ]);
    }

    /**
     * Store a newly created session log in storage.
     */
    public function store(Request $request, WeeklySlot $weeklySlot)
    {
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $weeklySlot->load(['students', 'client', 'teacher']);
        $students = $weeklySlot->students;
        if ($students->isEmpty() && $weeklySlot->client) {
            $students = collect([$weeklySlot->client]);
        }

        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'completion_status' => 'required|in:completed,no_show,technical_issue',
            'google_meet_link' => 'nullable|url',
            'extension_data' => 'nullable|string',
            'student_notes' => 'required|array',
            'student_notes.*' => 'nullable|string|max:2000',
        ]);

        $inactive = $students->first(fn($s) => !$s->hasActiveSubscription());
        if ($inactive) {
            return redirect()->route('teacher.schedule.index')
                             ->withErrors(['message' => 'فشل التسجيل. أحد الطلاب لا يملك اشتراكاً نشطاً أو رصيداً كافياً.']);
        }

        // Decrypt extension_data if encrypted; otherwise keep as-is
        $validated['extension_data'] = $this->decryptExtensionData($validated['extension_data'] ?? null) ?? ($validated['extension_data'] ?? null);

        // Compute session date/time for this week
        $today = Carbon::now();
        $lessonDate = $today->copy()->startOfWeek(Carbon::MONDAY);
        if ($weeklySlot->day_of_week > 0) {
            $lessonDate->addDays($weeklySlot->day_of_week - 1);
        } else {
            $lessonDate = $today->copy()->endOfWeek(Carbon::SUNDAY);
        }
        [$hour, $minute, $second] = explode(':', $weeklySlot->start_time);
        $startTime = $lessonDate->copy()->setTime($hour, $minute, $second);
        $endTime = $startTime->copy()->addHour();

        try {
            DB::transaction(function () use ($students, $validated, $weeklySlot, $startTime, $endTime) {
                foreach ($students as $student) {
                    $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
                    $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

                    $alreadyLogged = Appointment::where('teacher_id', $weeklySlot->teacher_id)
                        ->where('client_id', $student->id)
                        ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                        ->whereTime('start_time', $weeklySlot->start_time)
                        ->whereRaw('DAYOFWEEK(start_time) = ?', [$weeklySlot->day_of_week == 0 ? 1 : $weeklySlot->day_of_week + 1])
                        ->exists();

                    if ($alreadyLogged) {
                        continue;
                    }

                    Appointment::create([
                        'client_id' => $student->id,
                        'teacher_id' => $weeklySlot->teacher_id,
                        'subject' => $weeklySlot->teacher->subject ?? 'N/A',
                        'topic' => $validated['topic'],
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'status' => 'pending_verification',
                        'teacher_notes' => $validated['student_notes'][$student->id] ?? null,
                        'google_meet_link' => $validated['google_meet_link'] ?? null,
                        'extension_data' => $validated['extension_data'] ?? null,
                        'completion_status' => $validated['completion_status'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'حدث خطأ أثناء تسجيل الحصة: ' . $e->getMessage()]);
        }

        return redirect()->route('teacher.schedule.index')->with('status', 'تم تسجيل الحصة بنجاح وفي انتظار مراجعة الإدارة.');
    }

    /**
     * Decrypts the encrypted payload from the extension.
     * Expects JSON with {version, alg, encKey, iv, ciphertext, tag}.
     */
    private function decryptExtensionData(?string $payload): ?string
    {
        if (empty($payload)) return null;

        $json = json_decode($payload, true);
        if (
            !is_array($json) ||
            !isset($json['encKey'], $json['iv'], $json['ciphertext'], $json['tag'])
        ) {
            return $payload; // plaintext or unexpected; return as-is
        }

        $privKeyPem = env('MEETLIST_PRIV_KEY');
        if (!$privKeyPem) {
            return $payload; // no key configured
        }

        $privKey = openssl_pkey_get_private($privKeyPem);
        if (!$privKey) {
            return $payload;
        }

        $encKey = base64_decode($json['encKey']);
        $iv = base64_decode($json['iv']);
        $ciphertext = base64_decode($json['ciphertext']);
        $tag = base64_decode($json['tag']);

        // RSA-OAEP decrypt AES key
        $aesKey = null;
        if (!openssl_private_decrypt($encKey, $aesKey, $privKey, OPENSSL_PKCS1_OAEP_PADDING)) {
            return $payload;
        }

        // AES-256-GCM decrypt
        $plaintext = openssl_decrypt(
            $ciphertext,
            'aes-256-gcm',
            $aesKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        return $plaintext ?: $payload;
    }
}