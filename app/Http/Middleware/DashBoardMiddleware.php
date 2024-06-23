<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DashBoardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $Admin = 1;
        $Keuangan = 2;
        $Logistik = 3;
        $Direktur = 4;

        $allowedViewRoles = [$Admin,  $Keuangan, $Logistik , $Direktur];
        
        $user = Auth::user();
        if (!in_array($user->id_role, $allowedViewRoles)) {
            abort(403, 'Access Forbidden! You Dont Have Permission ! ');
        }

        return $next($request);


    }
}
