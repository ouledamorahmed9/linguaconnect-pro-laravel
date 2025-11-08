<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    /**
     * Display a listing of all clients.
     */
    public function index(): View
    {
        $clients = User::where('role', 'client')->latest()->get();
        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create(): View
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created client in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
        ]);

        return redirect()->route('admin.clients.index')->with('status', 'Client created successfully!');
    }

   
        /**
         * Show the form for editing the specified client.
         */
        public function edit(User $client): View
        {
            // Fetch the client's current active subscription
            $activeSubscription = $client->subscriptions()->where('status', 'active')->latest('starts_at')->first();

            return view('admin.clients.edit', [
                'client' => $client,
                'subscription' => $activeSubscription,
            ]);
        }
    /**
     * Update the specified client in the database.
     */
    public function update(Request $request, User $client): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($client->id)],
        ]);

        $client->update($request->only('name', 'email'));

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $client->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.clients.index')->with('status', 'Client profile updated successfully!');
    }

    /**
     * Remove the specified client from the database.
     */
    public function destroy(User $client): RedirectResponse
    {
        // Security check to prevent deleting admins or other non-client roles via this controller
        if (!$client->hasRole('client')) {
            return back()->with('error', 'Invalid user specified.');
        }

        $client->delete();

        return redirect()->route('admin.clients.index')->with('status', 'Client deleted successfully!');
    }
}

