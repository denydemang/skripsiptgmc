<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PurchaseRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        

        $Admin = 1;
        $Keuangan = 2;
        $Logistik = 3;
        $Direktur = 4;

        $user = Auth::user();
        $allowedViewRoles = [$Admin,  $Keuangan, $Logistik , $Direktur];
        $allowedUpdateRoles = [$Admin,$Logistik];
        $allowedDeleteRoles = [$Admin,$Logistik];
        $allowedCreateRoles = [$Admin, $Logistik];
        $allowedApproveRoles = [$Admin,$Direktur];
        $allowedPrintRoles = [$Direktur, $Admin, $Logistik];

        $viewRoute = ['admin.pr'];
        $updateRoute = ['admin.editprview'];
        $deleteRoute = ['admin.deletePR'];
        $createRoute = ['admin.addprview'];
        $approveRoute = ['admin.approvePR'];
        $printRoute = ['admin.printPR'];
        $routeName = $request->route()->getName();

        // VIEW AKSES

        if (in_array($routeName, $viewRoute )){
            if (!in_array( $user->id_role , $allowedViewRoles)){
                abort(403, 'Access Forbidden! You Dont Have Permission ! ');
            }

        }

        // UPDATE AKSES
        if (in_array($routeName, $updateRoute )){
            if (!in_array( $user->id_role , $allowedUpdateRoles)){
                abort(403, 'Access Forbidden! You Dont Have Permission ! ');
            }

        }

        // DELETE AKSES
        if (in_array($routeName, $deleteRoute )){
            if (!in_array( $user->id_role , $allowedDeleteRoles)){
                abort(403, 'Access Forbidden! You Dont Have Permission ! ');
            }

        }

        // CREATE AKSEs

        if (in_array($routeName, $createRoute )){
            if (!in_array( $user->id_role , $allowedCreateRoles)){
                abort(403, 'Access Forbidden! You Dont Have Permission ! ');
            }

        }

        //  APPROVE AKSES
        if (in_array($routeName, $approveRoute )){
            if (!in_array( $user->id_role , $allowedApproveRoles)){
                abort(403, 'Access Forbidden! You Dont Have Permission ! ');
            }
        }

        //  APPROVE AKSES
        if (in_array($routeName, $printRoute )){
            if (!in_array( $user->id_role , $allowedPrintRoles)){
                abort(403, 'Access Forbidden! You Dont Have Permission ! ');
            }
        }


        return $next($request);

    }
}
