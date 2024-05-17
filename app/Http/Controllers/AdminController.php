<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function terbilang($angka) {
        // Memisahkan bagian bulat dan desimal
        $parts = explode('.', $angka);
        $angka_bulat = abs($parts[0]);
        $angka_desimal = isset($parts[1]) ? $parts[1] : 0;
    
        // Konversi bagian bulat
        $terbilang_bulat = $this->terbilang_int($angka_bulat);
    
        // Konversi bagian desimal jika ada
        $terbilang_desimal = '';
        if ($angka_desimal > 0) {
            $terbilang_desimal = ' Koma ' . $this->terbilang_int($angka_desimal);
        }
    
        return $terbilang_bulat . $terbilang_desimal;
    }
    
    public function terbilang_int($angka) {
        $angka = (string) $angka;
        $length = strlen($angka);
        $terbilang = '';
        $angka_digit = array('', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan');
    
        if ($length == 1) {
            $terbilang = $angka_digit[$angka];
        } elseif ($length == 2) {
            if ($angka[0] == '1') {
                if ($angka[1] == '0') {
                    $terbilang = 'Sepuluh';
                } elseif ($angka[1] == '1') {
                    $terbilang = 'Sebelas';
                } else {
                    $terbilang = $angka_digit[$angka[1]] . ' Belas';
                }
            } else {
                $terbilang = $angka_digit[$angka[0]] . ' Puluh ' . $angka_digit[$angka[1]];
            }
        } elseif ($length == 3) {
            if ($angka[0] == '1') {
                $terbilang = 'Seratus ' . $this->terbilang_int((int) substr($angka, 1));
            } else {
                $terbilang = $angka_digit[$angka[0]] . ' Ratus ' . $this->terbilang_int((int) substr($angka, 1));
            }
        } elseif ($length <= 6) {
            $temp = $this->terbilang_int((int) substr($angka, 0, -3));
            $terbilang = $temp . ' Ribu ' . $this->terbilang_int((int) substr($angka, -3));
        } elseif ($length <= 9) {
            $temp = $this->terbilang_int((int) substr($angka, 0, -6));
            $terbilang = $temp . ' Juta ' . $this->terbilang_int((int) substr($angka, -6));
        } elseif ($length <= 12) {
            $temp = $this->terbilang_int((int) substr($angka, 0, -9));
            $terbilang = $temp . ' Miliar ' . $this->terbilang_int((int) substr($angka, -9));
        } elseif ($length <= 15) {
            $temp = $this->terbilang_int((int) substr($angka, 0, -12));
            $terbilang = $temp . ' Triliun ' . $this->terbilang_int((int) substr($angka, -12));
        }
    
        return $terbilang;
    }
    
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
        if (strpos($ex->getMessage(), "cannot delete or update a parent row")){
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
