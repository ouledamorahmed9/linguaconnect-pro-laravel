<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of sent messages.
     */
    public function index(): View
    {
        $messages = Message::where('sender_id', Auth::id())
                          ->with('recipient')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Show the form for creating a new message.
     */
    public function create(): View
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $coordinators = User:: where('role', 'coordinator')->orderBy('name')->get();

        return view('admin.messages.create', compact('teachers', 'coordinators'));
    }

    /**
     * Store a newly created message in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipient_type' => ['required', 'in:teacher,coordinator,all_teachers,all_coordinators'],
            'recipient_id' => ['required_if:recipient_type,teacher,coordinator', 'nullable', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
            'category' => ['required', 'in: general,announcement,notice,urgent'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $recipients = collect();

                // Determine recipients based on type
                if ($validated['recipient_type'] === 'all_teachers') {
                    $recipients = User::where('role', 'teacher')->get();
                } elseif ($validated['recipient_type'] === 'all_coordinators') {
                    $recipients = User::where('role', 'coordinator')->get();
                } else {
                    $recipients = collect([User::find($validated['recipient_id'])]);
                }

                // Create message for each recipient
                foreach ($recipients as $recipient) {
                    Message::create([
                        'sender_id' => Auth::id(),
                        'recipient_id' => $recipient->id,
                        'subject' => $validated['subject'],
                        'body' => $validated['body'],
                        'category' => $validated['category'],
                        'recipient_type' => $recipient->role,
                    ]);
                }
            });

            return redirect()->route('admin.messages.index')
                ->with('success', 'تم إرسال الرسالة بنجاح! ');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إرسال الرسالة:  ' . $e->getMessage());
        }
    }

    /**
     * Display the specified message.
     */
    public function show(Message $message): View
    {
        // Ensure admin can only view messages they sent
        if ($message->sender_id !== Auth::id()) {
            abort(403);
        }

        $message->load('recipient');

        return view('admin.messages.show', compact('message'));
    }

    /**
     * Remove the specified message from storage.
     */
    public function destroy(Message $message): RedirectResponse
    {
        if ($message->sender_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'تم حذف الرسالة بنجاح!');
    }
}