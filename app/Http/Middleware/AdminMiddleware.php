<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('home');
        }

        if (!str_ends_with($user->email, '@vallera.com')) {
            abort(403, 'Access denied. Only vallera.com users can access this area.');
        }

        if (!$user->is_admin) {
            abort(403, 'Access denied. You do not have admin privileges.');
        }

        return $next($request);
    }
}

