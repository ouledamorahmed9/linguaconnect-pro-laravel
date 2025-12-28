<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;

class PublicTeacherController extends Controller
{
    /**
     * Show the detailed public profile of a teacher.
     */
    public function show(User $teacher)
    {
        // Ensure user is actually a teacher
        if (!$teacher->hasRole('teacher')) {
            abort(404);
        }

        // Load reviews and count
        $teacher->load(['receivedReviews.client', 'studySubject']);
        
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Store a new review.
     */
    public function storeReview(Request $request, User $teacher)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // Prevent reviewing yourself
        if (auth()->id() === $teacher->id) {
            return back()->with('error', 'لا يمكنك تقييم نفسك.');
        }

        Review::create([
            'teacher_id' => $teacher->id,
            'client_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'تم إضافة تقييمك بنجاح!');
    }
}