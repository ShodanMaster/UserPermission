<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        $currentRoute = $request->route()->getName();

        $module = explode('.', $currentRoute)[0];

        $hasPermission = $user->routes()->where( 'title', $module)->exists();

        if (!$hasPermission) {
            return redirect()->back()->with('warning', 'Not Permitted');
        }

        return $next($request);
    }
}
