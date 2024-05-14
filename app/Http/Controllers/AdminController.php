<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard(Request $request){
        return response()
        ->view(
            "admin.dashboard",
        [
            'title' => 'Welcome To Dashboard',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName()

        ]);
    }

    public function errorException($ex, $routeRedirectName , $code =""){
        if (!strpos($ex->getMessage(), "cannot delete or update a parent row")){
            return response()->redirectToRoute($routeRedirectName)->with("error","Unable To Delete Or Update $code Already Used By Another Transaction");
        } else {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute($routeRedirectName)->with("error", $ex->getMessage());
         }
    }

     public function errorException2($ex, $code =""){
        if (strpos($ex->getMessage(), "cannot delete or update a parent row")){
            throw new Exception("Unable To Delete Or Update $code Already Used By Another Transaction");
        } else {
            throw new Exception($ex->getMessage());
         }
    }


    protected function isOutOfTermin(String $term, String $startDate ) :bool{

        switch ($term) {
            case 'n/30':
                $dueDate = Carbon::createFromFormat('Y-m-d', $startDate)->addDays(30);
                $dateNow = Carbon::now();

                $diffinDays = $dueDate->diffInDays($dateNow);
                if ($diffinDays < 0) {
                    // Tanggal saat ini melebihi dari tanggal due date
                    return true;
                } else {
                    return false;
                }

                break;
            case 'n/60':
                $dueDate = Carbon::createFromFormat('Y-m-d', $startDate)->addDays(60);
                $dateNow = Carbon::now();

                $diffinDays = $dueDate->diffInDays($dateNow);
                if ($diffinDays < 0) {
                    // Tanggal saat ini melebihi dari tanggal due date
                    return true;
                } else {
                    return false;
                }

                break;
            case 'n/90':
                $dueDate = Carbon::createFromFormat('Y-m-d', $startDate)->addDays(90);
                $dateNow = Carbon::now();

                $diffinDays = $dueDate->diffInDays($dateNow);
                if ($diffinDays < 0) {
                    // Tanggal saat ini melebihi dari tanggal due date
                    return true;
                } else {
                    return false;
                }

                break;
            default:
            return false;
            break;
        }

    }
}
