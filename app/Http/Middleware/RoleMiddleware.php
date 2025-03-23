<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roles)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $rolesArray = explode('|', $roles);
        $user = auth()->user();

        // Check if the user's role matches any of the allowed roles
        if (in_array($user->role, $rolesArray)) {
            return $next($request);
        }

        // Pass a custom message to the 403 error page
        return response()->view('errors.403', [
            'message' => 'You do not have the required role to access this page.'
        ], 403);
    }
}
