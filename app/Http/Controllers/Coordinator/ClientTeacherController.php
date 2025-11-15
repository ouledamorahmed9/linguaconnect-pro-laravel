<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientTeacherController extends Controller
{
    /**
     * ربط أو فصل عميل (يملكه المنسق) من معلم
     */
    public function toggle(Request $request, User $teacher)
    {
        $coordinator = Auth::user();
        
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:users,id',
            'checked' => 'required|boolean',
        ]);

        $client = User::find($validated['client_id']);

        // --- ** طبقة الأمان الأساسية ** ---
        // 1. التأكد أن المستخدم هو "عميل"
        // 2. التأكد أن هذا العميل "يتبع" للمنسق الحالي
        if (!$client || !$client->hasRole('client') || $client->created_by_user_id !== $coordinator->id) {
            return response()->json(['status' => 'error', 'message' => 'Invalid client.'], 403);
        }
        // --- ** نهاية طبقة الأمان ** ---

        // 3. التحقق من أهلية العميل (لديه اشتراك نشط)
        $isEligible = $client->hasActiveSubscription();

        // 4. تنفيذ الإجراء
        if ($validated['checked']) {
            // المنسق يريد "إضافة" العميل للمعلم
            
            if (!$isEligible) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Client subscription is not active. Cannot assign.'
                ], 422); // 422 Unprocessable Entity
            }
            
            $teacher->clients()->syncWithoutDetaching([$client->id]);
            return response()->json(['status' => 'attached', 'message' => 'Client assigned.']);

        } else {
            // المنسق يريد "إزالة" العميل من المعلم
            $teacher->clients()->detach($client->id);
            return response()->json(['status' => 'detached', 'message' => 'Client unassigned.']);
        }
    }
}