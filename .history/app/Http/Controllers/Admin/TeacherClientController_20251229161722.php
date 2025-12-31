<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeacherClientController extends Controller
{

    /**
     * NEW METHOD
     * Dynamically attach or detach a single client from a teacher.
     * This is used for the AJAX checkbox functionality.
     */
    public function toggle(Request $request, User $teacher)
    {
        // 1. Validate the incoming request
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:users,id',
            'checked' => 'required|boolean',
        ]);

        $client = User::find($validated['client_id']);

        // 2. Security Check: Is the user a client?
        if (!$client || !$client->hasRole('client')) {
            return response()->json(['status' => 'error', 'message' => 'Invalid client.'], 404);
        }

        // 3. Business Logic Check: Is the client eligible?
        $isEligible = $client->subscriptions()
                            ->where('status', 'active')
                            ->where('ends_at', '>', now())
                            ->whereColumn('lessons_used', '<', 'total_lessons')
                            ->exists();

        // 4. Perform the action
        if ($validated['checked']) {
            // User wants to ADD the client
            
            // Re-check eligibility as a final security measure
            if (!$isEligible) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Client subscription is not active. Cannot assign.'
                ], 422); // 422 Unprocessable Entity
            }
            
            // Add the client
            $teacher->clients()->syncWithoutDetaching([$client->id]);
            return response()->json(['status' => 'attached', 'message' => 'Client assigned.']);

        } else {
            // User wants to REMOVE the client
            $teacher->clients()->detach($client->id);
            return response()->json(['status' => 'detached', 'message' => 'Client unassigned.']);
        }
    }


    /**
     * Sync the list of clients for a teacher.
     * This method is no longer used by the new 'edit' page, but we leave it
     * for security and in case it's used elsewhere.
     */
    public function sync(Request $request, User $teacher)
    {
        $validated = $request->validate([
            'clients' => 'nullable|array',
            'clients.*' => 'integer|exists:users,id',
        ]);

        $clientIds = $validated['clients'] ?? [];

        // Security check: Filter out any clients who are not eligible
        $eligibleClientIds = User::whereIn('id', $clientIds)
            ->where('role', 'client')
            ->whereHas('subscriptions', function ($query) {
                $query->where('status', 'active')
                    ->where('ends_at', '>', now())
                    ->whereColumn('lessons_used', '<', 'total_lessons');
            })
            ->pluck('id')
            ->toArray();

        // We also need to keep clients who are *already* assigned,
        // even if their subscription expired (so admin can manually uncheck them).
        $alreadyAssignedIds = $teacher->clients()->pluck('users.id')->toArray();
        
        // The final list is:
        // 1. All eligible clients that were checked
        // 2. All *ineligible* clients that were *already assigned* and *remained checked*
        //    (This logic is complex, so we simplify: sync *only* eligible IDs)
        //    Let's refine: We will only sync the eligible clients from the request.
        
        // Safter logic:
        // Get all clients from the request
        $requestedClientIds = collect($request->input('clients', []));

        // Get all clients that are *actually* eligible
        $eligibleClients = User::whereIn('id', $requestedClientIds)
            ->whereHas('subscriptions', function ($query) {
                $query->where('status', 'active')
                      ->where('ends_at', '>', now())
                      ->whereColumn('lessons_used', '<', 'total_lessons');
            })
            ->pluck('id');

        // Get all clients who are *already* assigned
        $currentClients = $teacher->clients()->pluck('users.id');

        // Who to detach?
        // Clients who are currently assigned but *not* in the request.
        $toDetach = $currentClients->diff($requestedClientIds);
        $teacher->clients()->detach($toDetach);

        // Who to attach?
        // Clients who are in the request, eligible, and *not* already assigned.
        $toAttach = $eligibleClients->diff($currentClients);
        $teacher->clients()->attach($toAttach);

        return redirect()->route('admin.teachers.edit', $teacher)
                         ->with('status', 'تم تحديث قائمة العملاء بنجاح.');
    }
}

