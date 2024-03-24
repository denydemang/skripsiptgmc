<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Jika pengguna telah terautentikasi, lanjutkan ke rute yang diminta
            return $next($request);
        } else {
            // Jika pengguna belum terautentikasi, arahkan mereka ke halaman login
            return redirect()->route('getloginpage');
        }
    
    }
}
