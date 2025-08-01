<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select( 'id', 'name')->get();
        $routes = Route::select( 'id', 'title')->get();
        return view('permission', compact( 'users', 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'route_ids' => 'nullable|array',
            'route_ids.*' => 'exists:routes,id'
        ]);

        try {
            $user = User::findOrFail($validated['user_id']);

            $user->routes()->sync($validated['route_ids'] ?? []);

            return response()->json([
                'status' => 200,
                'message' => 'Permissions successfully updated.'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong while saving permissions.'
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
