<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Purchase;
use App\Models\Stock;
use App\Models\StocksAVG;
use App\Models\Supplier;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function floorWithPrecision($number, $precision = 0) {
        $factor = pow(10, $precision);
        return floor($number * $factor) / $factor;
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


        $currentYear = Carbon::now()->year;
        $lastYear = Carbon::now()->subYear()->year;

        // Jumlah proyek tahun ini
        $projectsThisYear = Project::whereYear('transaction_date', $currentYear)->count();

        // Jumlah proyek tahun lalu
        $projectsLastYear = Project::whereYear('transaction_date', $lastYear)->count();

        // Jumlah Pendapatan Tahun Ini
        $invoice  = Invoice::whereYear('transaction_date',  $currentYear)->select(DB::raw('ifnull(sum(paid_amount),0) as revenue'))->first();

        $revenue =  floatval($invoice->revenue);

        $invoice2  = Invoice::whereYear('transaction_date',  $lastYear)->select(DB::raw('ifnull(sum(paid_amount),0) as revenue'))->first();
        $piutangBelumBayar = Invoice::join('customers', 'customers.code', '=', 'invoices.customer_code')
                            ->select('customers.code as customer_code', 'customers.name as customer_name', DB::raw('ifnull(sum(invoices.grand_total - invoices.paid_amount),0) as total'))
                            ->whereColumn('invoices.grand_total', '>', 'invoices.paid_amount')
                            ->groupBy('customers.code', 'customers.name')->get();
        $utangBelumBayar = Purchase::join('suppliers', 'suppliers.code', '=', 'purchases.supplier_code')
                            ->select('suppliers.code as supplier_code', 'suppliers.name as supplier_name', DB::raw('ifnull(sum(purchases.grand_total - purchases.paid_amount),0) as total'))
                            ->whereColumn('purchases.grand_total', '>', 'purchases.paid_amount')
                            ->groupBy('suppliers.code', 'suppliers.name')->get();
        
        $stockReminder =  StocksAVG::Rightjoin("items", "stocksavg.item_code", "=", "items.code")
        ->join('categories', "categories.code", "=", "items.category_code")
        ->join('units', "units.code", "=", "items.unit_code")
        ->select('items.code as item_code' ,'items.name as item_name', 'categories.name as item_category', 'items.min_stock',
        'units.code as unit_code', DB::raw('ifnull(sum(stocksavg.actual_stock) - sum(stocksavg.used_stock) ,0) as current_stock'))
        ->groupBy('items.code', 'items.name' ,'categories.name' ,'units.code', 'items.min_stock')
        ->havingRaw('IFNULL(sum(stocksavg.actual_stock) - sum(stocksavg.used_stock), 0) <= (items.min_stock + 1)')->get();


        $projectDone = Project::whereColumn('budget', '<=', 'realisation_amount')->where('project_status', 1)->count();
        $projectOnProgress = Project::join('customers','customers.code', '=', 'projects.customer_code' )->select('customers.code as customer_code', 'customers.name as customer_name', 'projects.*')->whereColumn('budget', '>', 'realisation_amount')->where('project_status', 1)->get();
        $projectTotal = Project::count();
        $revenueLastYear =  floatval($invoice2->revenue);
        
        $totalCustomer = Customer::count();
        $totalSupplier = Supplier::count();

        if ($projectsLastYear == 0) {
            $percentageChange = $projectsThisYear > 0 ? 100 : 0; // Menghindari pembagian oleh nol
        } else {
            $percentageChange = (($projectsThisYear - $projectsLastYear) / $projectsLastYear) * 100;
        }

        if ($revenueLastYear == 0) {
            $percentageChangeInv = $revenue > 0 ? 100 : 0; // Menghindari pembagian oleh nol
        } else {
            $percentageChangeInv = (($revenue - $revenueLastYear) / $revenueLastYear) * 100;
        }

        return response()
        ->view(
            "admin.dashboard",
        [
            'title' => 'Welcome To Dashboard',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),   
            'totalCust' => $totalCustomer,
            'totalSupp' => $totalSupplier ,
            'totalProject' => $projectsThisYear,
            'percentageChange' => $percentageChange,
            'percentageChangeInv' => $percentageChangeInv,
            'revenue' => $revenue,
            'projectDone' => $projectDone,
            'projectTotal' =>$projectTotal,
            'piutangBelumBayar' => $piutangBelumBayar,
            'utangBelumBayar' => $utangBelumBayar,
            'stockReminder' =>$stockReminder,
            'projectOnProgress' =>$projectOnProgress
        ]);
    }

    public function errorException($ex, $routeRedirectName , $code =""){
        if (strpos($ex->getMessage(), "Cannot delete or update a parent row")){
            return response()->redirectToRoute($routeRedirectName)->with("error","Unable To Delete Or Update $code Already Used By Another Transaction");
        } else {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute($routeRedirectName)->with("error", $ex->getMessage());
         }
    }

     public function errorException2($ex, $code =""){
        if (strpos($ex->getMessage(), "Cannot delete or update a parent row")){
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
