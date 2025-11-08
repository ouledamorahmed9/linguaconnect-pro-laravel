<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request; // <-- ** STEP 1: Import Request **

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) // <-- ** STEP 2: Inject Request **
    {
        // ** STEP 3: Get the search term from the URL query string **
        $search = $request->input('search');

        // ** STEP 4: Start the query **
        $clientsQuery = User::where('role', 'client');

        // ** STEP 5: If there is a search term, filter the query **
        if ($search) {
            // This will search for the term in both 'name' and 'email' columns
            $clientsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // ** STEP 6: Paginate the final query and ensure pagination links keep the search term **
        $clients = $clientsQuery->orderBy('name')
                                ->paginate(15)
                                ->withQueryString(); // <-- This is the magic for pagination links

        // ** STEP 7: Pass both clients and the search term to the view **
        return view('admin.clients.index', [
            'clients' => $clients,
            'search' => $search, // <-- This lets us show the term in the search bar
        ]);
    }

    /**
     * ---
     * ** NEW REAL-TIME SEARCH METHOD **
     * ---
     * Handles AJAX requests from the search bar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $search = $request->input('search');

        $clientsQuery = User::where('role', 'client');

        if ($search) {
            $clientsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = $clientsQuery->orderBy('name')
                                ->paginate(15)
                                ->withQueryString(); // Ensures pagination links include the search query

        // Render just the table rows partial
        $table_rows_html = view('admin.clients._client-rows', [
            'clients' => $clients,
            'search' => $search
        ])->render();
        
        // Render just the pagination links
        $pagination_html = $clients->links()->toHtml();

        return response()->json([
            'rows' => $table_rows_html,
            'pagination' => $pagination_html,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'client',
        ]);

        return redirect()->route('admin.clients.index')->with('status', 'Client created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(User $client)
    {
        // Ensure we are only editing clients
        if ($client->role !== 'client') {
            abort(404);
        }

        // Get the client's subscription history
        $subscriptions = $client->subscriptions()->orderBy('created_at', 'desc')->get();
        
        return view('admin.clients.edit', [
            'client' => $client,
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $client)
    {
        // Ensure we are only updating clients
        if ($client->role !== 'client') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $client->id,
            'password' => 'nullable|string|min:8', // Make password optional
        ]);

        $client->name = $validated['name'];
        $client->email = $validated['email'];
        if (!empty($validated['password'])) {
            $client->password = bcrypt($validated['password']);
        }
        $client->save();

        return redirect()->route('admin.clients.edit', $client)->with('status', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $client)
    {
        // Ensure we are only deleting clients
        if ($client->role !== 'client') {
            abort(404);
        }
        
        $client->delete();
        return redirect()->route('admin.clients.index')->with('status', 'Client deleted successfully.');
    }
}