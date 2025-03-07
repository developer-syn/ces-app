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

        // Convert roles string to array (handles both 'admin' and 'admin,teacher' formats)
        $rolesArray = explode(',', $roles);

        $user = auth()->user();

        foreach($rolesArray as $role) {
            if($user->role === trim($role)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
