<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            switch ($user->role) {
                case 'administratorius':
                    return redirect('/admin/dashboard');
                case 'darbuotojas':
                    return redirect('/worker/dashboard');
                default:
                    return redirect('/customer/dashboard');
            }
        }
        return $next($request);
    }
}
