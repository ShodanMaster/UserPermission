<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetController extends Controller
{
    public function getUsers(Request $request){
        try{
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search');

            $query = User::select('id', 'name', 'username');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%");
                });
            }

            $users = $query->paginate($perPage);

            return response()->json([
                'status' => 200,
                'data' => $users->items(),
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ]);
        } catch (Exception $e) {

            Log::error("Error fetching users: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching Users'
            ], 500);
        }
    }

    public function getPermissions(Request $request)
    {
        try {
            $user = User::findOrFail($request->user_id);

            $routeIds = $user->routes->pluck('id');

            if ($routeIds->isNotEmpty()) {
                return response()->json([
                    'status' => 200,
                    'permissions' => $routeIds
                ]);
            }

            return response()->json([
                'status' => 204,
                'permissions' => []
            ]);

        } catch (Exception $e) {

            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }
}
