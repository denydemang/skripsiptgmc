<?php

namespace App\Http\Controllers;

use App\Models\CashBook;
use App\Models\CashBook_Detail;
use App\Models\CashBook_DetailB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CashBookController extends Controller
{
    public function getViewCashBook(Request $request){
        $supplyData = [
            'title' => 'CashBook',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
            ];

        return response()->view("admin.finance.cashbook",$supplyData);
    }
    public function getTableCashBook(Request $request, DataTables $dataTables ){
        if ($request->ajax()){
        
            $is_approve = intval($request->is_approve) >=  0  ? $request->is_approve : null ;
            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            
            
            $cashbook = CashBook::join("COA", "cash_books.COA_Cash", "=", "COA.code")
            ->whereBetween('transaction_date', [$startDate,$endDate])
            ->select("cash_books.*", "COA.code as coa_code", "COA.name as coa_name")
            ->when($is_approve !== null , function($query) use($is_approve){
                $query->where('is_approve', $is_approve);
            });

            return $dataTables->of($cashbook)
                ->editColumn('transaction_date', function($row) {
                    return Carbon::parse($row->transaction_date)->format('d/m/Y');
                })
                ->editColumn('total_transaction', function($row) {
                    return "Rp " .number_format($row->total_transaction,2, ',', '.');
                })
                ->editColumn('is_approve', function($row) {
                    $html ="";
                    switch ($row->is_approve) {
                        case 0:
                            $html= "<span class='badge badge-warning'>Not Approve</span>";
                            break;
                        case 1:
                            $html= "<span class='badge badge-primary'>Approved</span>";
                            break;
                    }
                    return $html;
                })
                ->editColumn('CbpType', function($row) {
                    $html ="";
                    switch ($row->CbpType) {
                        case "P":
                            return "Payment";
                            break;
                        case "R":
                            return "Receive";
                            break;
                    }
                })
                
                ->filterColumn('coa_code', function($query, $keyword) {
                    $query->whereRaw("coa.code LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('coa_name', function($query, $keyword) {
                    $query->whereRaw("coa.name as coa_name LIKE ?", ["%{$keyword}%"]);
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    switch ($row->is_approve) {
                        case 0: //Not Approved
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->cash_no.'" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger deletebtn" data-code="'.$row->cash_no.'" title="Delete"><i class="fa fa-trash"></i></button>
                            <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->cash_no.'" title="View Detail"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning approvebtn" data-code="'.$row->cash_no.'" title="Approve"><i class="fa fa-check"></i></button>
                            <a href="'.route('admin.printdetailcashbook',['id' => $row->cash_no]).'" target="_blank"><button class="btn btn-sm btn-info printbtn" data-code="'.$row->cash_no.'" title="Print cashbook"><i class="fa fa-print"></i></button></a>
                            </div>';
                            
                            # code...
                            break;
                            case 1: //Approved
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->cash_o.'" title="View Detail"><i class="fa fa-eye"></i></button>
                            <a href="'.route('admin.printdetailcashbook',['id' => $row->cash_no]).'" target="_blank"><button class="btn btn-sm btn-info printbtn mr-2" data-code="'.$row->cash_no.'" title="Print cashbook"><i class="fa fa-print"></i></button></a>
                            <a href="'.route('admin.printjurnalcashbook',['id' => $row->cash_no]).'" target="_blank"><button class="btn btn-sm btn-warning printbtn" data-code="'.$row->cash_no.'" title="Print Jurnal"><i class="fa fa-print"></i></button></a>
                            </div>';
                            
                            break;
                        
                        default:
                            # code...
                            break;
                    }
    
                    return $html;
                })
                ->rawColumns(['action','is_approve'])
                ->addIndexColumn()
                ->make(true);
                
        } else {
            abort(404);
        }

    }

    public function getTableCashBook1($code, Request $request, DataTables $dataTables ){
        if ($request->ajax()){

            $cashbook = CashBook_Detail::join("COA", "cash_book_details.coa", "=", "COA.code")
            ->select("cash_book_details.*", "COA.code as coa_code", "COA.name as coa_name")
            ->where("cash_no", "=", $code);

            return $dataTables->of($cashbook)
                ->editColumn('amount', function($row) {
                    return "Rp " .number_format($row->amount,2, ',', '.');
                })
                ->filterColumn('coa_code', function($query, $keyword) {
                    $query->whereRaw("coa.code LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('coa_name', function($query, $keyword) {
                    $query->whereRaw("coa.name as coa_name LIKE ?", ["%{$keyword}%"]);
                })
                ->addIndexColumn()
                ->make(true);
                
        } else {
            abort(404);
        }

    }
    public function getTableCashBook2($code, Request $request, DataTables $dataTables ){
        if ($request->ajax()){

            $cashbook = CashBook_DetailB::join("COA", "cash_books_detail_b.coa", "=", "COA.code")
            ->select("cash_books_detail_b.*", "COA.code as coa_code", "COA.name as coa_name")
            ->where("cash_no", "=", $code);

            return $dataTables->of($cashbook)
                ->editColumn('debit', function($row) {
                    return "Rp " .number_format($row->debit,2, ',', '.');
                })
                ->editColumn('kredit', function($row) {
                    return "Rp " .number_format($row->kredit,2, ',', '.');
                })
                ->filterColumn('coa_code', function($query, $keyword) {
                    $query->whereRaw("coa.code LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('coa_name', function($query, $keyword) {
                    $query->whereRaw("coa.name as coa_name LIKE ?", ["%{$keyword}%"]);
                })
                ->addIndexColumn()
                ->make(true);
                
        } else {
            abort(404);
        }

    }
    
    public function printjurnalcashbook($id){
            
    }

        
    public function printdetailcashbook($id){
            
    }
}
