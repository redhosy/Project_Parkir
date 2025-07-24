<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            if (Auth::check()) {
                // If user is logged in but wrong role
                return redirect('/')->with('error', 'Unauthorized access.');
            }
            // If user is not logged in
            return redirect('/login');
        }

        return $next($request);
    }
}
