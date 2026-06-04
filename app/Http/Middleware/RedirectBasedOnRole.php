<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     * Enforces role-based panel access after login.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return $next($request);
        }

        $currentPath = $request->path();

        // Faculty can only access faculty panel
        if ($user->hasRole('Faculty') && ! str_starts_with($currentPath, 'faculty')) {
            if (str_starts_with($currentPath, 'app') || str_starts_with($currentPath, 'registrar')) {
                return redirect('/faculty');
            }
        }

        // Registrar can only access registrar panel (unless they have other roles)
        if ($user->hasRole('Registrar') && ! $user->hasAnyRole(['Admin', 'Dean', 'Staff'])) {
            if (str_starts_with($currentPath, 'app') || str_starts_with($currentPath, 'faculty')) {
                return redirect('/registrar');
            }
        }

        // Admin/Dean/Staff should use app panel by default
        if ($user->hasAnyRole(['Admin', 'Dean', 'Staff']) && ! $user->hasRole('Faculty') && ! $user->hasRole('Registrar')) {
            if (str_starts_with($currentPath, 'faculty') || str_starts_with($currentPath, 'registrar')) {
                return redirect('/app');
            }
        }

        return $next($request);
    }
}
