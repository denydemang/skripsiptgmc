<?php

namespace App\Http\Controllers;

use App\Models\Advanced_Receipt;
use App\Models\Cash_Receive;
use App\Models\CashBook;
use App\Models\Invoice;
use App\Models\Journal;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectRealisation;
use App\Models\Purchase;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class JournalController extends AdminController
{
    public function getViewJournal(Request $request)
    {
        $supplyData = [
            'title' => 'Journal',
            'users' => Auth::user(),
            'sessionRoute' => $request->route()->getName(),
        ];

        return response()->view('admin.accounting.journals', $supplyData);
    }

    public function getViewJournalManage(Request $request, $code = null)
    {
        $data = [];
        if ($code) {
            //If In Update Mode

            $journal =Journal::join('journal_details', 'journals.voucher_no', '=', 'journal_details.voucher_no')
            ->join('coa', 'coa.code', '=', 'journal_details.coa_code')
            ->join('journal_types', 'journals.journal_type_code', '=', 'journal_types.code')
            ->select('journals.*', 'journal_details.*', 'coa.name as coa_name', 'journal_types.name as journal_type_name')
            ->where('journals.voucher_no', $code)->get();

            if (count($journal) == 0) {
                abort(404);
            }

            $data['journal'] = $journal;
        }
        $supplyData = [
            'title' => $request->route()->getName() == 'admin.addJournalView' ? 'Add New Journal' : 'Edit Journal',
            'users' => Auth::user(),
            'sessionRoute' => $request->route()->getName(),
            'data' => $data,
        ];

        return response()->view('admin.accounting.journalmanage', $supplyData);
    }

    public function getTableJournal(Request $request, DataTables $dataTables)
    {
        if ($request->ajax()) {
            $posting_status = intval($request->posting_status) >= 0 ? $request->posting_status : null;
            $journal_type_code = $request->journal_type_code ? $request->journal_type_code : null;
            $startDate = Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');

            $journal = Journal::join('journal_types', 'journals.journal_type_code', '=', 'journal_types.code')
                ->select('journals.*', 'journal_types.name as journal_type_name')
                ->whereBetween('transaction_date', [$startDate, $endDate])

                ->when($posting_status !== null, function ($query) use ($posting_status) {
                    $query->where('posting_status', $posting_status);
                })
                ->when($journal_type_code !== null, function ($query) use ($journal_type_code) {
                    $query->where('journal_type_code', $journal_type_code);
                });

            return $dataTables
                ->of($journal)
                ->editColumn('transaction_date', function ($row) {
                    return Carbon::parse($row->transaction_date)->format('d/m/Y');
                })
                ->editColumn('posting_status', function ($row) {
                    $html = '';
                    switch ($row->posting_status) {
                        case 0:
                            $html = "<span class='badge badge-warning'>Unposted</span>";
                            break;
                        case 1:
                            $html = "<span class='badge badge-primary'>Posted</span>";
                            break;
                    }
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    switch ($row->posting_status) {
                        case 0: //Not Approved
                            $html =
                                '
                            <div class="d-flex justify-content-left">
                            <button class="btn btn-sm btn-primary editbtn" data-code="' .
                                $row->voucher_no .
                                '" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger deletebtn" data-code="' .
                                $row->voucher_no .
                                '" title="Delete"><i class="fa fa-trash"></i></button>
                            <button class="btn btn-sm btn-success viewbtn" data-code="' .
                                $row->voucher_no .
                                '" title="View Detail"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning postingbtn" data-code="' .
                                $row->voucher_no .
                                '" title="Post Journal"><i class="fas fa-pen-nib"></i></button>
                            </div>';

                            # code...
                            break;
                        case 1: //Approved
                            $html =
                                '
                            <div class="d-flex justify-content-left">
                            <button class="btn btn-sm btn-success viewbtn" data-code="' .
                                $row->voucher_no .
                                '" title="View Detail"><i class="fa fa-eye"></i></button>

                            </div>';

                            break;

                        default:
                            # code...
                            break;
                    }

                    return $html;
                })
                ->filterColumn('journal_type_name', function ($query, $keyword) {
                    $query->whereRaw('journal_types.name LIKE ?', ["%{$keyword}%"]);
                })
                ->rawColumns(['action', 'posting_status'])
                ->addIndexColumn()
                ->make(true);
        } else {
            abort(404);
        }
    }

    public function detailjournal($id, Request $request)
    {
        if ($request->ajax()) {
            $dataDetail = Journal::join('journal_details', 'journals.voucher_no', '=', 'journal_details.voucher_no')->join('coa', 'coa.code', '=', 'journal_details.coa_code')->join('journal_types', 'journals.journal_type_code', '=', 'journal_types.code')->select('journals.*', 'journal_details.*', 'coa.name as coa_name', 'journal_types.name as journal_type_name')->where('journals.voucher_no', $id)->get();

            return json_encode($dataDetail);
        } else {
            abort(404);
        }
    }

    public function postingjournal($id)
    {
        try {
            //code...
            Journal::where('voucher_no', $id)->update([
                'posting_status' => 1,
            ]);

            return response()
                ->redirectToRoute('admin.journal')
                ->with('success', "Data Journal $id Successfully Posted");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorException($th, 'admin.journal', $id);
        }
    }

    public function deletejournal($id)
    {
        try {
            //code...

            DB::beginTransaction();
            $journal = Journal::where('voucher_no', $id)->first();

            $AR = Advanced_Receipt::where('adr_no', $journal->ref_no)->first();
            $cashReceive = Cash_Receive::where('bkm_no', $journal->ref_no)->first();
            $cashbook = CashBook::where('cash_no', $journal->ref_no)->first();
            $invoice = Invoice::where('invoice_no', $journal->ref_no)->first();
            $payment = Payment::where('bkk_no', $journal->ref_no)->first();
            $Purchase = Purchase::where('purchase_no', $journal->ref_no)->first();
            $Receipt = Project::where('code', $journal->ref_no)->first();
            $ProjectRealisation = ProjectRealisation::where('code', $journal->ref_no)->first();

            if ($AR || $cashReceive || $cashbook || $invoice || $payment || $Purchase || $Receipt || $ProjectRealisation) {
                throw new Exception("Cannot Delete Journal :  $journal->voucher_no , Already Linked To Transaction $journal->ref_no");
            } else {
                $journal->delete();
            }

            Db::commit();
            return response()
                ->redirectToRoute('admin.journal')
                ->with('success', "Data Journal $id Successfully Deleted");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorException($th, 'admin.journal', $id);
            //throw $th;
        }
    }

    public function addjournal(Request $request){
        if($request->ajax()){
    
            try {


                //code...
                DB::beginTransaction();
                $data = $request->all();

                $data['detail']= json_decode($data['detail']);
                $journalUmum = new AccountingController;
                $newCode =  $journalUmum->JournalUmum($data);

                DB::commit();
                Session::flash('success',  "New Journal : $newCode Succesfully Created");
                return json_encode(true);
                
            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            }



        } else {
            abort(404);
        }
    }

    public function editJournal(Request $request ,$code){
        if($request->ajax()){
    
            try {
                //code...
                DB::beginTransaction();
                $data = $request->all();

                $data['detail']= json_decode($data['detail']);
                $journalUmum = new AccountingController;
                $newCode =  $journalUmum->JournalEdit($data, $code);

                DB::commit();
                Session::flash('success',  "Journal : $code Succesfully Updated");
                return json_encode(true);
                
            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            }



        } else {
            abort(404);
        }
    }
    public function printjournalrecap(Request $request){
  
        $posting_status = intval($request->posting_status) >= 0 ? $request->posting_status : null;
        $journal_type_code = $request->journaltype ? $request->journaltype : null;
        $startDate = $request->startDate;
        $endDate =  $request->endDate;
        $endDate =  $request->endDate;

        $printcontroller = new PrintController();
        return $printcontroller->journalrecap($startDate,$endDate, $posting_status, $journal_type_code);
        
    }
}
