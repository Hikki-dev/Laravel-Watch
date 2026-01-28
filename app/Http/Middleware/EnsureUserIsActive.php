<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_active) {
            // Only attempt to logout and invalidate session if we are using the web guard (stateful)
            // This prevents errors with stateless guards like Sanctum in API tests or calls
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            if ($request->expectsJson()) {
                 return response()->json(['message' => 'Your account has been deactivated.'], 403);
            }

            return redirect()->route('login')->with('status', 'Your account has been deactivated. Please contact support.');
        }

        return $next($request);
    }
}
