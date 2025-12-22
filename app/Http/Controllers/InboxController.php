<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{
    /**
     * Display the inbox. 
     */
    public function index(): View
    {
        $messages = Message:: where('recipient_id', Auth::id())
                          ->with('sender')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        $unreadCount = Message::where('recipient_id', Auth::id())
                              ->unread()
                              ->count();

        return view('inbox.index', compact('messages', 'unreadCount'));
    }

    /**
     * Display the specified message.
     */
    public function show(Message $message): View
    {
        // Ensure user can only view their own messages
        if ($message->recipient_id !== Auth::id()) {
            abort(403);
        }

        // Mark as read
        $message->markAsRead();
        $message->load('sender');

        return view('inbox.show', compact('message'));
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(Message $message)
    {
        if ($message->recipient_id !== Auth::id()) {
            abort(403);
        }

        $message->markAsRead();

        return redirect()->back()->with('success', 'تم تحديد الرسالة كمقروءة');
    }

    /**
     * Mark all messages as read. 
     */
    public function markAllAsRead()
    {
        Message::where('recipient_id', Auth::id())
               ->unread()
               ->update([
                   'is_read' => true,
                   'read_at' => now(),
               ]);

        return redirect()->back()->with('success', 'تم تحديد جميع الرسائل كمقروءة');
    }
}