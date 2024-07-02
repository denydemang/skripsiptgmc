<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MasterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $roleUser = Auth::user()->id_role;

        if (!$roleUser) {
            return redirect('/login');
        }


        // ['admin', 'r_unit', 'r_item', 'r_category', 'r_customer', 'r_supplier', 'r_role', 'r_upah']
        // $main_menu= [0, 1, 2, 3, 4, 5, 6, 7];

        // ['index', 'create', 'store', 'edit', 'update', 'destroy', 'users', 'getDataUsers', 'updateDataUser', 'addDataUsers', 'deleteDataUser', 'editUsers', 'getTableItemSearch', 'CustomerGetForModal', 'SupplierGetForModal', 'getroles', 'getsuppliers', 'getcustomers', 'getcategorys', 'getitems', 'getUnits']

        // Ambil rute saat ini
        $currentRoute = $request->route()->getName();
        $ex_route = explode('.',$currentRoute);
        $routemurni = $ex_route[0];
        $routeaction = $ex_route[1];
        $call_route_aksess = getRouteChecker_MDLR($roleUser, $routemurni);


        if (in_array($currentRoute, $call_route_aksess)) {
            return $next($request);
        } else {
            if ($routeaction=='destroy' || $routeaction=='deleteDataUser' || $routeaction=='deletecoa' || $routeaction=='deletecoasub') {
                return response()->json(['msg' => '403 - Access Forbidden! You Dont Have Permission Access !', 'status' => 'error', 'code' => 403]);
            } else {
                abort(403, 'Access Forbidden! You Dont Have Permission Access ! ');
            }
        }

    }
}
