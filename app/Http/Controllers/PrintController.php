<?php

namespace App\Http\Controllers;

use App\Models\Advanced_Receipt;
use App\Models\Cash_Receive;
use App\Models\CashBook;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Journal;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Project_Detail;
use App\Models\Project_Detail_B_Realisation;
use App\Models\Project_Detail_Realisations;
use App\Models\ProjectDetailB;
use App\Models\ProjectRealisation;
use App\Models\Purchase;
use App\Models\Purchase_Request;
use App\Models\Stock;
use App\Models\Stocks_Out;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PrintController extends AdminController
{
    public function printJournalProject($id){

        $journal = Project::join("journals",  "projects.code", "=", "journals.ref_no")
        ->join("journal_details", "journals.voucher_no", "=", "journal_details.voucher_no")
        ->join("coa", "journal_details.coa_code", "=", "coa.code")
        ->select("projects.name as project_name", "coa.name as coa_name", "journals.*", "journal_details.*")
        ->where("projects.code", $id)
        ->where("journals.journal_type_code", "!=", "jp")
        ->where("journal_details.description", "not like", "%Realisasi%")
        ->orderBy("id", "asc")
        ->get();

        if (count($journal) == 0){
            abort(404);
        };

        $totalDebit = 0;
        $totalKredit = 0;

        foreach($journal as $j){
            $totalDebit +=  floatval($j->debit);
            $totalKredit += floatval($j->kredit);
        }

        $data =[
            "dataprojectdanjurnal" => $journal,
            // "jurnalpenyesuaian" => $journalPenyesuaian,
            // "jurnalRealisasi" =>  $journalFinish,
            "totalDebit" => $totalDebit,
            "totalKredit" => $totalKredit,
            // "totalDebitPenyesuaian" => $totalDebitPenyesuaian,
            // "totalKreditPenyesuian" => $totalKreditPenyesuian,
            // "totalDebitRealisasi" => $totalDebitRealisasi ,
            // "totalKreditRealisasi" => $totalKreditRealisasi ,
        ];
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadview("admin.project.print.printjournalproject", $data);
        return $pdf->stream("JournalPorject-$id.pdf", array("Attachment" => false));
    }

    public function printProject($id){

        $project = Project::join('type_projects', 'projects.project_type_code', '=', 'type_projects.code')
        ->join('customers','projects.customer_code', '=', 'customers.code' )
        ->select('projects.*', 'type_projects.name as type_project_name', 'type_projects.description as type_project_description',
        'customers.name as customer_name', 'customers.address as customer_address')
        ->where("projects.code", $id)
        ->first();

        $projectrealisation = ProjectRealisation::where("project_code", $id)->orderBy('realisation_date', 'asc')->orderBy("code", "asc")->get();

        $dataBahanBaku =  DB::select("
            
        SELECT
        pd.project_code,
        pd.item_code,
        i.name as item_name,
        pd.unit_code,
        pd.qty as qty_estimated,
        IFNULL(qry.total_qty,0) as qty_used,
        (pd.qty - IFNULL(qry.total_qty,0)) as qty_remaining
        FROM
        project_details pd

        LEFT JOIN
        (
        SELECT
        pr.project_code,
        pdr.item_code,
        sum(IFNULL(pdr.qty_used,0)) as total_qty

        FROM
        project_realisations pr LEFT JOIN project_detail_realisations pdr 
        ON pr.`code` = pdr.project_realisation_code

        GROUP BY pr.project_code, pdr.item_code) qry

        ON pd.project_code = qry.project_code AND pd.item_code = qry.item_code

        INNER JOIN items i
        ON i.`code` = pd.item_code

        GROUP BY pd.project_code,pd.item_code,i.name ,pd.qty ,pd.unit_code, qry.total_qty
        Having pd.project_code = ?
    
        ", [$id]);

        $dataUpah = DB::select("
        SELECT
        qry.project_code,
        qry.upah_code,
        qry.job_name,
        (qry.price * qry.balance_awal) as amount_estimated,
        (qry.price * qry.total_used) as amount_realised
        from
        (   SELECT
            pdb.project_code,
            pdb.upah_code,
            u.job as job_name,
            pdb.unit,
            pdb.price,
            pdb.qty as balance_awal,
            IFNULL(qry.total_qty,0) as total_used,
            (pdb.qty - IFNULL(qry.total_qty,0)) as last_balance
            FROM
            project_detail_b pdb

            LEFT JOIN
            (
            SELECT
            pr.project_code,
            pdbr.upah_code,
            sum(IFNULL(pdbr.qty_used,0)) as total_qty

            FROM
            project_realisations pr LEFT JOIN project_detail_b_realisations pdbr 
            ON pr.`code` = pdbr.project_realisation_code 

            GROUP BY pr.project_code, pdbr.upah_code) qry

            ON pdb.project_code = qry.project_code AND pdb.upah_code = qry.upah_code 

            INNER JOIN upah u
            ON u.`code` = pdb.upah_code

            GROUP BY pdb.project_code,pdb.upah_code,u.job,pdb.unit,pdb.price, pdb.qty, qry.total_qty
            Having pdb.project_code = ?
        ) qry
        
        ", [$id]);
        $data['project'] = $project;
        $data['bahanBaku'] = $dataBahanBaku;
        $data['upah'] = $dataUpah;
        $data['projectrealisation'] = $projectrealisation;
        $data['currenttermin'] = count($projectrealisation);
        $data['percentprogress'] = count($projectrealisation) > 0 ? $projectrealisation[count($projectrealisation) - 1]['percent_realisation'] : 0;

        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'potrait');
        $pdf->loadview("admin.project.print.printproject", $data);
        return $pdf->stream("ProjectReport.pdf", array("Attachment" => false));
    }

    public function printprojectrecap($statuscode = null,$startdate,$lastdate, $customercode = null){

    
        $project = Project::join('type_projects', 'projects.project_type_code', '=', 'type_projects.code')
        ->join('customers','projects.customer_code', '=', 'customers.code' )
        ->select('projects.*', 'type_projects.name as type_project_name', 'type_projects.description as type_project_description',
        'customers.name as customer_name', 'customers.address as customer_address')
        ->whereBetween('transaction_date' , [$startdate,$lastdate])
        ->when($statuscode !== null , function($query) use($statuscode){
            switch (intval($statuscode)) {
                case 0:
                    $query->where('project_status', 0);
                    break;
                case 1:
                    $query->where('project_status', 1);
                    $query->whereColumn('budget', '>', 'realisation_amount');
                    break;
                case 2:
                    $query->whereColumn('budget', '<=', 'realisation_amount');
                    break;
            }
        })
        ->when($customercode !== null, function($query) use($customercode){
            $query->where("projects.customer_code", $customercode);
        })
        ->get();

    $data = [
        "projects" => $project,
        "customer_code" => $customercode,
        "statuscode"=> $statuscode,
        "startDate" => $startdate,
        "endDate" => $lastdate,
    ];



    $pdf = App::make('dompdf.wrapper');
    $pdf->setPaper('A4', 'landscape');
    $pdf->loadview("admin.project.print.printprojectrecap", $data);
    return $pdf->stream("ProjectRecapitulation($startdate-$lastdate).pdf", array("Attachment" => false));
        try {

        } catch (\Throwable $th) {
            abort(404);
        }


    }

    public function printpurchaserequest($id){


        $PurchaseRequest = Purchase_Request::join("purchase_request_details", "purchase_requests.pr_no" , "=", "purchase_request_details.pr_no")
                        ->leftJoin("items", 'items.code', "=", "purchase_request_details.item_code")
        ->where("purchase_requests.pr_no", $id)->get();

        if (count($PurchaseRequest) == 0){
            abort(404);
        };
        $data = [
            "dataPR" =>  $PurchaseRequest
        ];
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'potrait');
        $pdf->loadview("admin.inventory.print.printpurchaserequest" ,$data);
        return $pdf->stream("PurchaseRequest-($id).pdf", array("Attachment" => false));
    }

    public function printIIN($firstDate, $lastDate){



        $stock = Stock::join("items", "stocks.item_code", "=", "items.code")
        ->join('categories', "categories.code", "=", "items.category_code")
        ->select('stocks.id', DB::raw('DATE(stocks.item_date) as item_date'), 'stocks.ref_no',
        'stocks.item_code' ,'items.name as item_name', 'categories.name as item_category',
        'stocks.unit_code', 'stocks.actual_stock', 'stocks.cogs', 'categories.coa_code')
        ->whereBetween('stocks.item_date', [$firstDate,$lastDate])->get();

        $groupedData = collect($stock)->groupBy('item_date');

        $data = [
            'stocckData' => $groupedData,
            'firstDate' => Carbon::parse($firstDate)->format("d/m/Y"),
            'lastDate' => Carbon::parse($lastDate)->format("d/m/Y")
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadview("admin.inventory.print.printinventoryin", $data);
        return $pdf->stream("IIN-($firstDate to $lastDate).pdf", array("Attachment" => false));
    }

    public function printIOUT($firstDate, $lastDate){



        $stock = Stocks_Out::join("items", "stocks_out.item_code", "=", "items.code")
        ->join('categories', "categories.code", "=", "items.category_code")
        ->select('stocks_out.id', DB::raw('DATE(stocks_out.item_date) as item_date'), 'stocks_out.ref_no',
        'stocks_out.item_code' ,'items.name as item_name', 'categories.name as item_category',
        'stocks_out.unit_code', 'stocks_out.qty', 'stocks_out.cogs', 'categories.coa_code')
        ->whereBetween('stocks_out.item_date', [$firstDate,$lastDate])->get();

        $groupedData = collect($stock)->groupBy('item_date');

        $data = [
            'stocckData' => $groupedData,
            'firstDate' => Carbon::parse($firstDate)->format("d/m/Y"),
            'lastDate' => Carbon::parse($lastDate)->format("d/m/Y")
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadview("admin.inventory.print.printinventoryout", $data);
        return $pdf->stream("IOUT-($firstDate to $lastDate).pdf", array("Attachment" => false));
    }

    public function printStock(){

        $stock = Stock::Rightjoin("items", "stocks.item_code", "=", "items.code")
        ->join('categories', "categories.code", "=", "items.category_code")
        ->join('units', "units.code", "=", "items.unit_code")
        ->select('items.code as item_code' ,'items.name as item_name', 'categories.name as item_category',
        'units.code as unit_code', DB::raw('sum(stocks.actual_stock) as actual_stock'), DB::raw('sum(stocks.used_stock) as used_stock'),
        DB::raw('sum(stocks.actual_stock) - sum(stocks.used_stock) as available_stock'))
        ->groupBy('items.code', 'items.name' ,'categories.name' ,'units.code')->get();

        $data = [
            'stocckData' => $stock,
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadview("admin.inventory.print.printrecapstock", $data);
        return $pdf->stream("RecapStock.pdf", array("Attachment" => false));
    }

    public function printStockReminder(){

        $stock = Stock::Rightjoin("items", "stocks.item_code", "=", "items.code")
        ->join('categories', "categories.code", "=", "items.category_code")
        ->join('units', "units.code", "=", "items.unit_code")
        ->select('items.code as item_code' ,'items.name as item_name', 'categories.name as item_category', 'items.min_stock',
        'units.code as unit_code', DB::raw('ifnull(sum(stocks.actual_stock) - sum(stocks.used_stock) ,0) as current_stock'))
        ->groupBy('items.code', 'items.name' ,'categories.name' ,'units.code', 'items.min_stock')
        ->havingRaw('IFNULL(sum(stocks.actual_stock) - sum(stocks.used_stock), 0) <= (items.min_stock + 1)')->get();


        $data = [
            'stocckData' => $stock,
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadview("admin.inventory.print.printreminderstock", $data);
        return $pdf->stream("ReminderStock.pdf", array("Attachment" => false));
    }

    public function printstockcard( $startDate , $lastDate , $itemCode){

        $item = Item::where("code" , $itemCode)->first();

        if(!$item){
            abort(404);
        }

        $BeginningBalance = DB::select('
        Select

        qry.item_code,
        qry.item_name,
        qry.ref_no,
        qry.unit_code,
        Round(qry.actual_stock - qry.pengurang, 2) as beginning_balance,
        qry.cogs,
        qry.cogs * (Round(qry.actual_stock - qry.pengurang, 2)) as total_cogs

        from

        (Select
            a.item_code,
            i.name as item_name,
            a.ref_no ,
            a.unit_code,
            a.actual_stock,
            (Select ifnull(sum(qty),0) from stocks_out where stocks_out.stock_id = a.id AND item_date < ?) as pengurang,
            a.cogs

        from stocks a
        join items i
        on i.code = a.item_code
        where a.item_code = ? AND
        a.item_date < ?  ) as qry

        where
        Round(qry.actual_stock - qry.pengurang, 2) > 0
        ', [$startDate, $itemCode, $startDate]);




        $ITEMIN = Stock::join("items", "items.code", "=", "stocks.item_code")
        ->select('stocks.item_date', 'stocks.ref_no' , 
        DB::raw("
        CASE 
            WHEN SUBSTR(stocks.ref_no, 1, 3) = 'PRC' THEN 'Pembelian' 
            WHEN SUBSTR(stocks.ref_no, 1, 4) = 'PRJR' THEN 'Sisa Material Proyek'
            ELSE '-'
        End As keterangan 
        "),
        'stocks.item_code' , "items.name as item_name", 'stocks.unit_code' , 'stocks.actual_stock as qty' , 'stocks.cogs',DB::raw('stocks.actual_stock * stocks.cogs as total_cogs'))
        ->where("stocks.item_code", $itemCode)
        ->whereBetween('stocks.item_date', [$startDate, $lastDate]);


        $ITEMOUT = Stocks_Out::join("items", "items.code", "=", "stocks_out.item_code")
        ->select('stocks_out.item_date','stocks_out.ref_no' ,
        DB::raw("
        CASE 
            WHEN SUBSTR(stocks_out.ref_no, 1, 3) = 'PRJ' THEN 'Pemakaian Proyek' 
            ELSE '-'
        End As keterangan
        "),
        'stocks_out.item_code' , "items.name as item_name", 'stocks_out.unit_code', 'stocks_out.qty', 'stocks_out.cogs' , DB::raw('stocks_out.qty * stocks_out.cogs as total_cogs'))
        ->where("stocks_out.item_code", $itemCode)
        ->whereBetween("stocks_out.item_date",  [$startDate, $lastDate]);


        $UNIONALLRESULT = $ITEMIN->UnionALL($ITEMOUT)
                        ->orderBy('item_date', 'asc')->orderBy('ref_no' ,'asc')->get();


        $data = [
            'itemDesc' =>  $item ,
            'startDate' => $startDate,
            'lastDate' => $lastDate,
            'begginningstock' => $BeginningBalance,
            'stocks' => $UNIONALLRESULT,
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A3', 'landscape');
        $pdf->loadview("admin.inventory.print.printstockcard" , $data);
        return $pdf->stream("StockCard.pdf", array("Attachment" => false));
    }

    public function printpurchasedetail($id){
        $Purchase = Purchase::join("purchase_details", "purchases.purchase_no" , "=", "purchase_details.purchase_no")
        ->join("suppliers", "purchases.supplier_code" , "=", "suppliers.code")
        ->leftJoin("items", 'items.code', "=", "purchase_details.item_code")
        ->select('purchases.*', 'purchases.total as total_item_purchase','purchases.created_by as dibuat_oleh','items.name as item_name',  DB::raw('ifnull(suppliers.name ,"-" ) as supplier_name'),  DB::raw('ifnull(suppliers.address ,"-" ) as supplier_address'), DB::raw('ifnull(suppliers.phone,"-" ) as supplier_phone'),"purchase_details.*")
        ->where("purchases.purchase_no", $id)->get();


        if (count($Purchase) == 0){
        abort(404);
        };
        $data = [
        "dataPurchase" => $Purchase
        ];
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'potrait');
        $pdf->loadview("admin.transactions.prints.printPurchase" ,$data);
        return $pdf->stream("Purchase-($id).pdf", array("Attachment" => false));
    }

    public function printjurnalpurchase($id){

        try {
            //code...
            $journal = Journal::join('journal_details', "journals.voucher_no", "=", "journal_details.voucher_no")
            ->join("coa", "journal_details.coa_code", "=", "coa.code")
            ->where("journals.ref_no", $id)
            ->select("coa.name", "journals.*", "journal_details.*")
            ->orderBy("journal_details.debit", 'desc')
            ->get();

            if (count($journal) == 0){
                throw new Exception();
            }

            $Purchase = Purchase::join("suppliers", "purchases.supplier_code" , "=", "suppliers.code")
            ->select('purchases.*','purchases.created_by as dibuat_oleh',  DB::raw('ifnull(suppliers.name ,"-" ) as supplier_name'),  DB::raw('ifnull(suppliers.address ,"-" ) as supplier_address'), DB::raw('ifnull(suppliers.phone,"-" ) as supplier_phone'))
            ->where("purchases.purchase_no", $id)->get();

            $data = [
                "purchaseData" => $Purchase,
                "journal" => $journal,
            ];


            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.transactions.prints.printJurnalPurchase", $data);
            return $pdf->stream("JournalPurchase-$id.pdf", array("Attachment" => false));

        } catch (\Throwable $th) {
            abort(404);
        }


    }

    public function printrecappurchase($suppliercode ,$startDate, $endDate, $is_approve, $paidStatus){

        try {
            //code...
            $purchase = Purchase::join("suppliers", "purchases.supplier_code" , "=", "suppliers.code")
            ->select('purchases.*', DB::raw('ifnull(suppliers.name ,"-" ) as supplier_name'),  DB::raw('ifnull(suppliers.address ,"-" ) as supplier_address'), DB::raw('ifnull(suppliers.phone,"-" ) as supplier_phone'))
            ->whereBetween('transaction_date', [$startDate,$endDate])
            ->when($is_approve !== null , function($query) use($is_approve){
                $query->where('is_approve', $is_approve);
            })
            ->when($suppliercode !== null , function($query) use($suppliercode){
                $query->where('supplier_code', $suppliercode);
            })
            ->when($paidStatus !== null , function($query) use($paidStatus){
                    switch (intval($paidStatus)) {
                        case 0:
                            # Unpaid
                            $query->whereRaw("
                            CASE WHEN payment_term_code LIKE 'n/30' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 30 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount = 0
                            WHEN payment_term_code LIKE 'n/60' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 60 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount = 0
                            WHEN payment_term_code LIKE 'n/90' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 90 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount = 0
                            ELSE 0
                            END = 1

                            ");

                            break;
                        case 1:
                            # Fullpaid
                            $query->whereRaw("grand_total - paid_amount <= 0");

                            break;
                        case 2:
                            # Outstanding
                            $query->whereRaw("
                            CASE WHEN payment_term_code LIKE 'n/30' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 30 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount > 0
                            WHEN payment_term_code LIKE 'n/60' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 60 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount > 0
                            WHEN payment_term_code LIKE 'n/90' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 90 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount > 0
                            ELSE 0
                            END = 1

                            ");

                            break;
                        case 3:
                            # Overdue
                            $query->whereRaw("CASE WHEN payment_term_code LIKE 'n/30' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 30 DAY)) > 0 AND grand_total - paid_amount > 0
                            WHEN payment_term_code LIKE 'n/60' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 60 DAY)) > 0 AND grand_total - paid_amount > 0
                            WHEN payment_term_code LIKE 'n/90' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 90 DAY)) > 0 AND grand_total - paid_amount > 0
                            ELSE 0
                            END = 1 ");

                            break;

                    }
            })->get();

            $data = [
                "purchase" => $purchase,
                "paidStatus" =>$paidStatus,
                "is_approve"=> $is_approve,
                "startDate" => $startDate,
                "endDate" => $endDate,
                'suppliercode'=> $suppliercode
            ];

            // return $data;

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.transactions.prints.printrecappurchase", $data);
            return $pdf->stream("PurchaseRecap($startDate-$endDate).pdf", array("Attachment" => false));
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
            // abort(404);
        }


    }

    public function printjurnalpayment($id){

        try {
            //code...
            $journal = Journal::join('journal_details', "journals.voucher_no", "=", "journal_details.voucher_no")
            ->join("coa", "journal_details.coa_code", "=", "coa.code")
            ->where("journals.ref_no", $id)
            ->select("coa.name", "journals.*", "journal_details.*")
            ->orderBy("journal_details.debit", 'desc')
            ->get();

            if (count($journal) == 0){
                throw new Exception();
            }

            $payment = Payment::join("suppliers", "payments.supplier_code" , "=", "suppliers.code")
            ->select('payments.*', DB::raw('ifnull(suppliers.name ,"-" ) as supplier_name'),
                DB::raw('ifnull(suppliers.address ,"-" ) as supplier_address'),
                DB::raw('ifnull(suppliers.phone,"-" ) as supplier_phone'))
            ->where("bkk_no", $id)
            ->first();

            $data = [
                "payment" => $payment,
                "journal" => $journal,
            ];

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.finance.prints.printjurnalpayment", $data);
            return $pdf->stream("JournalPayment-$id.pdf", array("Attachment" => false));

        } catch (\Throwable $th) {
            abort(404);
        }


    }

    public function printdetailpayment($id){
        try {
            //code...
            $payment = Payment::join("suppliers", "payments.supplier_code" , "=", "suppliers.code")
            ->select('payments.*', DB::raw('ifnull(suppliers.name ,"-" ) as supplier_name'),  DB::raw('ifnull(suppliers.address ,"-" ) as supplier_address'), DB::raw('ifnull(suppliers.phone,"-" ) as supplier_phone'))
            ->where("payments.bkk_no" , $id)->first();


            if (!$payment){
                throw new Exception();
            }

            $data = [
                "payment" => $payment,
            ];

            $pdf = App::make('dompdf.wrapper');
            $customPaper = array(0,0,660,460);
            $pdf->setPaper($customPaper);
            $pdf->loadview("admin.finance.prints.printdetailpayment", $data);
            return $pdf->stream("$id.pdf", array("Attachment" => false));

        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        //    return $th->getMes!;
        }
    }

    public function printrecappayment($suppliercode ,$startDate, $endDate, $is_approve){

        try {

            //code...
            $payment = Payment::join("suppliers", "payments.supplier_code" , "=", "suppliers.code")
            ->select('payments.*', DB::raw('ifnull(suppliers.name ,"-" ) as supplier_name'),  DB::raw('ifnull(suppliers.address ,"-" ) as supplier_address'), DB::raw('ifnull(suppliers.phone,"-" ) as supplier_phone'))
            ->whereBetween('transaction_date', [$startDate,$endDate])
            ->when($is_approve !== null , function($query) use($is_approve){
                $query->where('is_approve', $is_approve);
            })
            ->when($suppliercode !== null , function($query) use($suppliercode){
                $query->where('supplier_code', $suppliercode);
            })->get();

            $data = [
                "payments" => $payment,
                "is_approve"=> $is_approve,
                "startDate" => $startDate,
                "endDate" => $endDate,
                'suppliercode'=> $suppliercode
            ];

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.finance.prints.printrecappayment", $data);
            return $pdf->stream("PaymentRecap($startDate-$endDate).pdf", array("Attachment" => false));
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
            // abort(404);
        }

    }

    public function printcashbookjournal($id){
        try {
            //code...
            $journal = Journal::join('journal_details', "journals.voucher_no", "=", "journal_details.voucher_no")
            ->join("coa", "journal_details.coa_code", "=", "coa.code")
            ->where("journals.ref_no", $id)
            ->select("coa.name", "journals.*", "journal_details.*")
            ->orderBy("journal_details.debit", 'desc')
            ->get();

            if (count($journal) == 0){
                throw new Exception();
            }

            $cashbook = CashBook::where("cash_no", $id)->first();

            $data = [
                "cashbook" => $cashbook,
                "journal" => $journal,
            ];

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.finance.prints.printjurnalcashbook", $data);
            return $pdf->stream("JournalCashBook-$id.pdf", array("Attachment" => false));

        } catch (\Throwable $th) {
            abort(404);
        }
    }

    public function printjurnalar($id){
        try {
            //code...
            $journal = Journal::join('journal_details', "journals.voucher_no", "=", "journal_details.voucher_no")
            ->join("coa", "journal_details.coa_code", "=", "coa.code")
            ->where("journals.ref_no", $id)
            ->select("coa.name", "journals.*", "journal_details.*")
            ->orderBy("journal_details.debit", 'desc')
            ->get();

            if (count($journal) == 0){
                throw new Exception();
            }

            $advancedreceipt = Advanced_Receipt::where("adr_no", $id)
                                ->join("customers", "advanced_receipts.customer_code", "=", "customers.code")
                                ->select("advanced_receipts.*", 'customers.code as customer_code', "customers.name as customer_name")
                                ->first();

            $data = [
                "advancedreceipt" => $advancedreceipt,
                "journal" => $journal,
            ];

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.finance.prints.printjurnaladvancedreceipt", $data);
            return $pdf->stream("Journaladvancedreceipt-$id.pdf", array("Attachment" => false));

        } catch (\Throwable $th) {
            abort(404);
        }
    }

    public function printdetailadr($id){
        try {
            //code...
            $AdvancedReceipt = Advanced_Receipt::join("customers", "advanced_receipts.customer_code" , "=", "customers.code")
            ->select('advanced_receipts.*', DB::raw('ifnull(customers.name ,"-" ) as customer_name'),  DB::raw('ifnull(customers.address ,"-" ) as customer_address'), DB::raw('ifnull(customers.phone,"-" ) as customer_phone'))
            ->where("advanced_receipts.adr_no" , $id)->first();

            if (!$AdvancedReceipt){
                throw new Exception();
            }

            $data = [
                "AdvancedReceipt" => $AdvancedReceipt,
                "Terbilang" => $this->terbilang(floatval($AdvancedReceipt->deposit_amount))
            ];

            $pdf = App::make('dompdf.wrapper');
            $customPaper = array(0,0,660,460);
            $pdf->setPaper($customPaper);
            $pdf->loadview("admin.finance.prints.printdetailAdvancedReceipt", $data);
            return $pdf->stream("AdvancedReceipt($id).pdf", array("Attachment" => false));

        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        //    return $th->getMes!;
        }
    }

    public function printrecapar($customercode ,$startDate, $endDate, $is_approve){

        try {

            //code...
            $AR = Advanced_Receipt::join("customers", "advanced_receipts.customer_code" , "=", "customers.code")
            ->select('advanced_receipts.*', DB::raw('ifnull(customers.name ,"-" ) as customer_name'),  DB::raw('ifnull(customers.address ,"-" ) as customer_address'), DB::raw('ifnull(customers.phone,"-" ) as customer_phone'))
            ->whereBetween('transaction_date', [$startDate,$endDate])
            ->when($is_approve !== null , function($query) use($is_approve){
                $query->where('is_approve', $is_approve);
            })
            ->when($customercode !== null , function($query) use($customercode){
                $query->where('customer_code', $customercode);
            })->get();

            $data = [
                "AR" => $AR,
                "is_approve"=> $is_approve,
                "startDate" => $startDate,
                "endDate" => $endDate,
                'customercode'=> $customercode
            ];

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.finance.prints.printrecapar", $data);
            return $pdf->stream("AdvancedReceiptRecap($startDate-$endDate).pdf", array("Attachment" => false));
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }

    }

    public function printjurnalinvoice($id){


        try {
            //code...
            $journal = Journal::join('journal_details', "journals.voucher_no", "=", "journal_details.voucher_no")
            ->join("coa", "journal_details.coa_code", "=", "coa.code")
            ->where("journals.ref_no", $id)
            ->select("coa.name", "journals.*", "journal_details.*")
            ->orderBy("journal_details.debit", 'desc')
            ->get();

            if (count($journal) == 0){
                throw new Exception();
            }

            $invoice = Invoice::where("invoice_no", $id)
                                ->join("customers", "invoices.customer_code", "=", "customers.code")
                                ->select("invoices.*", 'customers.code as customer_code', "customers.name as customer_name")
                                ->first();

            $data = [
                "invoice" => $invoice,
                "journal" => $journal,
            ];

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.transactions.prints.printjurnalinvoice", $data);
            return $pdf->stream("JournalInvoice-$id.pdf", array("Attachment" => false));
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    public function printdetailinvoice($id){
        $invoices = Invoice::join("customers", "customers.code" , "=", "invoices.customer_code")
        ->join("project_realisations", "project_realisations.code" , "=", "invoices.project_realisation_code")
        ->join("projects", "projects.code" , "=", "project_realisations.project_code")
        ->select('invoices.*', 'projects.name as project_name', "projects.code as project_code","customers.code as customer_code", "customers.name as customer_name",
                "project_realisations.code as project_realisation_code" ,'project_realisations.termin','projects.total_termin', "project_realisations.percent_realisation", "project_realisations.realisation_amount" ,"projects.budget as project_amount")
        ->where("invoices.invoice_no", $id)->first();

        // return $invoices;

        if (!$invoices){
        abort(404);
        };
        $data = [
        "invoices" => $invoices,
        "terbilang" => $this->terbilang(floatval($invoices['grand_total']) - floatval($invoices['paid_amount']))
        ];
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'potrait');
        $pdf->loadview("admin.transactions.prints.printdetailinvoice",$data);
        return $pdf->stream("Invoice-($id).pdf", array("Attachment" => false));

    }

    public function printrecapinvoice($customercode ,$startDate, $endDate, $is_approve, $paidStatus){

        try {


            //code...
            $invoice = Invoice::join("customers", "invoices.customer_code" , "=", "customers.code")
            ->select('invoices.*', DB::raw('ifnull(customers.name ,"-" ) as customer_name'),  DB::raw('ifnull(customers.address ,"-" ) as customer_address'), DB::raw('ifnull(customers.phone,"-" ) as customer_phone'))
            ->whereBetween('invoices.transaction_date', [$startDate,$endDate])
            ->when($is_approve !== null , function($query) use($is_approve){
                $query->where('is_approve', $is_approve);
            })
            ->when($customercode !== null , function($query) use($customercode){
                $query->where('customer_code', $customercode);
            })
            ->when($paidStatus !== null , function($query) use($paidStatus){
                    switch (intval($paidStatus)) {
                        case 0:
                            # Unpaid
                            $query->whereRaw("
                            CASE WHEN payment_term_code LIKE 'n/30' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 30 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount = 0
                            WHEN payment_term_code LIKE 'n/60' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 60 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount = 0
                            WHEN payment_term_code LIKE 'n/90' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 90 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount = 0
                            ELSE 0
                            END = 1

                            ");

                            break;
                        case 1:
                            # Fullpaid
                            $query->whereRaw("grand_total - paid_amount <= 0");

                            break;
                        case 2:
                            # Outstanding
                            $query->whereRaw("
                            CASE WHEN payment_term_code LIKE 'n/30' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 30 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount > 0
                            WHEN payment_term_code LIKE 'n/60' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 60 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount > 0
                            WHEN payment_term_code LIKE 'n/90' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 90 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount > 0
                            ELSE 0
                            END = 1

                            ");

                            break;
                        case 3:
                            # Overdue
                            $query->whereRaw("CASE WHEN payment_term_code LIKE 'n/30' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 30 DAY)) > 0 AND grand_total - paid_amount > 0
                            WHEN payment_term_code LIKE 'n/60' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 60 DAY)) > 0 AND grand_total - paid_amount > 0
                            WHEN payment_term_code LIKE 'n/90' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 90 DAY)) > 0 AND grand_total - paid_amount > 0
                            ELSE 0
                            END = 1 ");

                            break;

                    }
            })->get();


            $data = [
                "invoice" => $invoice,
                "paidStatus" =>$paidStatus,
                "is_approve"=> $is_approve,
                "startDate" => $startDate,
                "endDate" => $endDate,
                'customercode'=> $customercode
            ];

            // return $data;

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.transactions.prints.printrecapinvoice", $data);
            return $pdf->stream("PurchaseRecap($startDate-$endDate).pdf", array("Attachment" => false));
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
            // abort(404);
        }
    }

    public function printjurnalreceipt($id){

        try {
            //code...
            $journal = Journal::join('journal_details', "journals.voucher_no", "=", "journal_details.voucher_no")
            ->join("coa", "journal_details.coa_code", "=", "coa.code")
            ->where("journals.ref_no", $id)
            ->select("coa.name", "journals.*", "journal_details.*")
            ->orderBy("journal_details.debit", 'desc')
            ->get();

            if (count($journal) == 0){
                throw new Exception();
            }

            $receipt = Cash_Receive::join("customers", "cash_receives.customer_code" , "=", "customers.code")
            ->select('cash_receives.*', DB::raw('ifnull(customers.name ,"-" ) as customer_name'),
                DB::raw('ifnull(customers.address ,"-" ) as customer_address'),
                DB::raw('ifnull(customers.phone,"-" ) as customer_phone'))
            ->where("bkm_no", $id)
            ->first();

            $data = [
                "receipt" => $receipt,
                "journal" => $journal,
            ];

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.finance.prints.printjurnalreceipt", $data);
            return $pdf->stream("JournalReceipt-$id.pdf", array("Attachment" => false));

        } catch (\Throwable $th) {
            abort(404);
        }


    }

    public function printdetailreceipt($id){
        try {
            //code...
            $receives = Cash_Receive::join("customers", "cash_receives.customer_code" , "=", "customers.code")
            ->join('invoices', 'cash_receives.ref_no', '=', 'invoices.invoice_no')
            ->join('project_realisations', 'project_realisations.code', '=', 'invoices.project_realisation_code')
            ->join('projects', 'project_realisations.project_code', '=', 'projects.code')
            ->select('cash_receives.*','cash_receives.ref_no as invoice_no',  'project_realisations.code as project_realisation_code','projects.code as project_code', 'projects.name as project_name',
            DB::raw('ifnull(customers.name ,"-" ) as customer_name'),
            DB::raw('ifnull(customers.address ,"-" ) as customer_address'),
            DB::raw('ifnull(customers.phone,"-" ) as customer_phone'))
            ->where("cash_receives.bkm_no" , $id)->first();

            $receives->terbilang = $this->terbilang(floatval($receives->total_amount));


            if (!$receives){
                throw new Exception();
            }

            $data = [
                "receives" => $receives,
            ];

            $pdf = App::make('dompdf.wrapper');
            $customPaper = array(0,0,660,460);
            $pdf->setPaper($customPaper);
            $pdf->loadview("admin.finance.prints.printdetailreceipt", $data);
            return $pdf->stream("BKM-Invoice$receives->invoice_no.pdf", array("Attachment" => false));

        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        //    return $th->getMes!;
        }
    }

    public function printrecapreceipt($customercode,$startDate, $endDate, $is_approve){

        try {

            //code...
            $receipt = Cash_Receive::join("customers", "cash_receives.customer_code" , "=", "customers.code")
            ->select('cash_receives.*', DB::raw('ifnull(customers.name ,"-" ) as customer_name'),  DB::raw('ifnull(customers.address ,"-" ) as customer_address'), DB::raw('ifnull(customers.phone,"-" ) as customer_phone'))
            ->whereBetween('transaction_date', [$startDate,$endDate])
            ->when($is_approve !== null , function($query) use($is_approve){
                $query->where('is_approve', $is_approve);
            })
            ->when($customercode !== null , function($query) use($customercode){
                $query->where('cash_receives.customer_code', $customercode);
            })->get();

            $data = [
                "receipts" => $receipt,
                "is_approve"=> $is_approve,
                "startDate" => $startDate,
                "endDate" => $endDate,
                'customercode'=> $customercode
            ];


            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.finance.prints.printrecapreceipt", $data);
            return $pdf->stream("ReceiptRecap($startDate-$endDate).pdf", array("Attachment" => false));
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
            // abort(404);
        }

    }

    public function printledgerreport(string $startDate, string $endDate, array $listCOACODE = []){

        
        $coaString = "(". "'" . implode("', '", $listCOACODE) . "'" . ")";
        try {
            $result  = "";
            if (count($listCOACODE) > 0){
                $result = DB::select(
                    "
                    SELECT 
                    qrybeginbalance.code,
                    qrybeginbalance.name,
                    qrybeginbalance.default_dk,
                    qrybeginbalance.BeginBalance,
                    transaksi.transaction_date,
                    transaksi.voucher_no,
                    transaksi.description,
                    transaksi.debit,
                    transaksi.kredit FROM
                        (
        
                            SELECT 
                            qry.code,
                            coa.name,
                            coa.default_dk,
                            sum(qry.BeginBalance) as BeginBalance
        
                            from
        
                            (
                            SELECT
                                    a.`code` ,
                                        CASE
                                            WHEN a.default_dk = 'D' THEN
                                            ifnull(sum(jd.debit), 0) - ifnull(sum(jd.kredit),0)
                                            ELSE
                                            ifnull(sum(jd.kredit),0) - ifnull(sum(jd.debit), 0)
                                        END AS BeginBalance
        
                                        FROM
                                                coa a JOIN journal_details jd ON a.`code` = jd.coa_code
                                                JOIN journals j ON jd.voucher_no = j.voucher_no
                                                WHERE
                                        j.posting_status = 1 AND
                                        CAST(j.transaction_date as DATE) < :startDate1
                                                group by a.code, a.default_dk
                                UNION ALL
                                SELECT coa.code ,coa.beginning_balance as BeginBalance from coa where coa.description = 'Detail'
                            ) qry
        
                            INNER JOIN coa ON coa.code = qry.`code`
        
                            Where coa.code IN $coaString
                            GROUP BY qry.code, coa.name, coa.default_dk
                            ORDER BY qry.`code`
                        ) qrybeginbalance
        
                        LEFT JOIN 
        
                        (
                            SELECT
                                    j.transaction_date,
                                    j.voucher_no,
                                    jd1.coa_code,
                                    jd1.debit,
                                    jd1.description,
                                    jd1.kredit 
                            FROM
                                    journals j
                                    INNER JOIN journal_details jd1 ON j.voucher_no = jd1.voucher_no
                            WHERE
                                    j.posting_status = 1 AND
                                    CAST(j.transaction_date as DATE) between :startDate2 AND :endDate1 
                                    AND jd1.coa_code IN $coaString
                        ) transaksi	
                                
                    ON qrybeginbalance.code = transaksi.coa_code
                    ORDER BY qrybeginbalance.code, transaksi.transaction_date ASC
                    
                    " , ['startDate1' => $startDate,
                        'startDate2' => $startDate, 
                        'endDate1' =>$endDate]
                
                    );
                        
                
            } else {
                $result = DB::select(
                    "
                    SELECT 
                    qrybeginbalance.code,
                    qrybeginbalance.name,
                    qrybeginbalance.default_dk,
                    qrybeginbalance.BeginBalance,
                    transaksi.transaction_date,
                    transaksi.voucher_no,
                    transaksi.description,
                    transaksi.debit,
                    transaksi.kredit FROM
                        (
        
                            SELECT 
                            qry.code,
                            coa.name,
                            coa.default_dk,
                            sum(qry.BeginBalance) as BeginBalance
        
                            from
        
                            (
                            SELECT
                                    a.`code` ,
                                        CASE
                                            WHEN a.default_dk = 'D' THEN
                                            ifnull(sum(jd.debit), 0) - ifnull(sum(jd.kredit),0)
                                            ELSE
                                            ifnull(sum(jd.kredit),0) - ifnull(sum(jd.debit), 0)
                                        END AS BeginBalance
        
                                        FROM
                                                coa a JOIN journal_details jd ON a.`code` = jd.coa_code
                                                JOIN journals j ON jd.voucher_no = j.voucher_no
                                                WHERE
                                        j.posting_status = 1 AND
                                        CAST(j.transaction_date as DATE) < :startDate1
                                                group by a.code, a.default_dk
                                UNION ALL
                                SELECT coa.code ,coa.beginning_balance as BeginBalance from coa where coa.description = 'Detail'
                            ) qry
        
                            INNER JOIN coa ON coa.code = qry.`code`
                            GROUP BY qry.code, coa.name, coa.default_dk
                            ORDER BY qry.`code`
                        ) qrybeginbalance
        
                        LEFT JOIN 
        
                        (
                            SELECT
                                    j.transaction_date,
                                    j.voucher_no,
                                    jd1.coa_code,
                                    jd1.debit,
                                    jd1.description,
                                    jd1.kredit 
                            FROM
                                    journals j
                                    INNER JOIN journal_details jd1 ON j.voucher_no = jd1.voucher_no
                            WHERE
                                    j.posting_status = 1 AND
                                    CAST(j.transaction_date as DATE) between :startDate2 AND :endDate1 
                        ) transaksi	
                                
                    ON qrybeginbalance.code = transaksi.coa_code
                    ORDER BY qrybeginbalance.code, transaksi.transaction_date ASC
                    
                    " , ['startDate1' => $startDate,
                        'startDate2' => $startDate, 
                        'endDate1' =>$endDate]
                
                    );
                        
            }

            $groupedData = collect($result)->groupBy('code');
            
    
            $data = [
                'coaData' => $groupedData,
                'firstDate' => Carbon::parse($startDate)->format("d/m/Y"),
                'lastDate' => Carbon::parse($endDate)->format("d/m/Y")
            ];

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.accounting.prints.printledgerreport",$data);
            return $pdf->stream("BukuBesar($startDate-$endDate).pdf", array("Attachment" => false));

            } catch (\Throwable $th) {
                abort(404);
            }
    }

    public function printtrialbalancereport(string $startDate, string $endDate){
        
        $result = DB::select(
            "
            SELECT
            qry2.code,
            qry2.name,
            qry2.BeginningBalance,
            qry2.CurrentDebit,
            qry2.CurrentCredit,
            case when substring(qry2.Code,1,1) in('1', '5', '6', '9') then -- Aktiva, Dan Beban (default debit)

            qry2.BeginningBalance + qry2.CurrentDebit - qry2.CurrentCredit

            else			-- Utang dan modal default kredit			
                
            qry2.BeginningBalance + qry2.CurrentCredit - qry2.CurrentDebit

            end as LastBalance
            from
            (
            SELECT 

            mastercoa.code,
            mastercoa.name,
            case when substring(qry.Code,1,1) in('1', '5', '6', '9') then -- Aktiva, Dan Beban (default debit)

            ifnull(mastercoa.beginning_balance,0) + ifnull(sum(qry.BeginningDebit) - sum(qry.BeginningCredit),0)

            else			-- Utang dan modal default kredit				
                    
            ifnull(mastercoa.beginning_balance,0) + ifnull(sum(qry.BeginningCredit) - sum(qry.BeginningDebit),0)

            end as BeginningBalance,
            ifnull(sum(qry.CurrentDebit),0) as CurrentDebit,
            ifnull(sum(qry.CurrentCredit) ,0)as CurrentCredit

            FROM
            (
            SELECT 
            coa.code,
            coa.name ,
            coa.beginning_balance
            from coa

            where description='detail' 

            ) mastercoa

            LEFT JOIN
            (SELECT
            case
            when cast(journals.transaction_date as date) < :startDate1 then
            case when SUBSTR(journal_details.coa_code,1,1) in('1','2','3') then
            journal_details.coa_code
            else
            case when month(journals.transaction_date) = month(:startDate2) then
                journal_details.coa_code
            else
                '30.02.01' -- labaditahan
            end
            end
            else
            journal_details.coa_code
            end as Code,
            case
            when cast(journals.transaction_date as date) < :startDate3 then
            ifnull(journal_details.Debit,0)
            else
            0
            end as BeginningDebit,

            case
            when cast(journals.transaction_date as date) < :startDate4 then
            ifnull(journal_details.Kredit,0)
            else
            0
            end as BeginningCredit,

            case
            when cast(journals.transaction_date as date) between :startDate5 AND  :endDate1 then
            ifnull(journal_details.Kredit,0)
            else
            0
            end as CurrentCredit,

            case
            when cast(journals.transaction_date as date) between :startDate6 AND :endDate2 then
            ifnull(journal_details.Debit,0)
            else
            0
            end as CurrentDebit

            from journals INNER JOIN journal_details ON journals.voucher_no =journal_details.voucher_no 
            where cast(journals.transaction_date as date) <= :endDate3 AND journals.posting_status = 1
            ) qry

            ON mastercoa.code = qry.Code
            GROUP BY mastercoa.code, mastercoa.name, mastercoa.beginning_balance, qry.Code
            ) qry2
            
            " , [ 'startDate1' => $startDate , 
                'startDate2'=> $startDate ,
                'startDate3' => $startDate ,
                'startDate4' => $startDate, 
                'startDate5' =>$startDate,
                'endDate1' => $endDate,
                'startDate6' => $startDate,
                'endDate2' => $endDate,
                'endDate3' => $endDate,
                ]
        
            );

    
            $data = [
                'coaData' => $result,
                'firstDate' => Carbon::parse($startDate)->format("d/m/Y"),
                'lastDate' => Carbon::parse($endDate)->format("d/m/Y")
            ];

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'potrait');
            $pdf->loadview("admin.accounting.prints.printtrialbalancereport",$data);
            return $pdf->stream("TrialBalance($startDate-$endDate).pdf", array("Attachment" => false));

        try {
            //code...


        } catch (\Throwable $th) {
            abort(404);
        }
    }
    
    public function printtriallrreport(string $startDate, string $endDate){

        try {
            //code...

            $result = DB::select(
                "

                SELECT 
                    coa.type as Tipe,
                    coa.code ,
                    coa.name ,
                    coa.description,
                    sum(jd.debit) as debit,
                    sum(jd.kredit) as kredit,
                    sum(jd.kredit) - sum(jd.debit) as Total 

                FROM
                coa
                    INNER JOIN journal_details jd
                    ON coa.`code` = jd.coa_code
                    INNER JOIN journals j 
                    ON jd.voucher_no = j.voucher_no
                where SUBSTR(coa.`code`,1,1) NOT IN (1,2,3) AND
                coa.description ='Detail' AND
                j.transaction_date between ? AND ? AND
                j.posting_status = 1

                GROUP BY 
                coa.code,
                coa.name,
                coa.description,
                coa.type

                ORDER BY
                coa.code
                
                " , [$startDate,$endDate]
            
                );

                $groupedData = collect($result)->groupBy('Tipe');
        
                $data = [
                    'coaData' => $groupedData,
                    'firstDate' => Carbon::parse($startDate)->format("d/m/Y"),
                    'lastDate' => Carbon::parse($endDate)->format("d/m/Y")
                ];

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'potrait');
                $pdf->loadview("admin.accounting.prints.printlabarugi",$data);
                return $pdf->stream("LabaRugi($startDate-$endDate).pdf", array("Attachment" => false));

        } catch (\Throwable $th) {
            abort(400);
        }
    }

    public function journalrecap(string $startDate, string $endDate, $isposting = null, $journaltype= null){

       
        try {
            
            $journal = Journal::join('journal_details as jd', 'journals.voucher_no', '=', 'jd.voucher_no')
            ->join("coa", "coa.code", "=", "jd.coa_code")
            ->select("journals.voucher_no", "journals.transaction_date", "coa.code", "coa.name", 'jd.debit', "jd.kredit" ,"jd.description")
            ->whereBetween('journals.transaction_date', [$startDate,$endDate])
            ->when($isposting !== null , function($query) use($isposting){
                $query->where("posting_status" , intval($isposting));
            })
            ->when($journaltype !== null , function($query) use($journaltype){
                $query->where("journal_type_code" , $journaltype);
            })
            ->groupBy("journals.voucher_no" ,"journals.transaction_date", "coa.code", "coa.name", 'jd.debit', "jd.kredit", "jd.description")
            ->orderBy("journals.transaction_date", "asc")->orderBy("journals.voucher_no", "asc")->get();

            $groupedData = collect($journal)->groupBy('voucher_no');
            $data = [
                'statusposting' => $isposting,
                'journaltype' => $journaltype,
                'coaData' => $groupedData,
                'firstDate' => Carbon::parse($startDate)->format("d/m/Y"),
                'lastDate' => Carbon::parse($endDate)->format("d/m/Y")
            ];

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'potrait');
            $pdf->loadview("admin.accounting.prints.printjournalrecap",$data);
            return $pdf->stream("JournalRecap($startDate-$endDate).pdf", array("Attachment" => false));
                
            
        } catch (\Throwable $th) {
            abort(404);
        }


    }

    public function printbalancesheetreport(string $endDate){

        try {
            //code...
            $result = DB::select(
                "
                
                    -- AKTIVA
                    SELECT
                    coa.type,
                    CASE SUBSTR(coa.`code` ,1,5)
                    WHEN  '10.01' THEN
                            'AKTIVA LANCAR'
                    WHEN '10.02' THEN
                            'AKTIVA TETAP' END AS header,
                    coa.code,		
                    coa.`name`,
                    coa.beginning_balance + IFNULL(qry.Total,0) as balance
                    FROM
                    (	SELECT 
                            coa.code,
                            coa.name,
                            IFNULL(SUM(journal_details.debit),0) AS Debit, 
                            IFNULL(SUM(journal_details.kredit),0) AS Credit,
                            IFNULL(SUM(journal_details.debit),0) - (IFNULL(SUM(journal_details.kredit),0))  AS Total
                            FROM coa
                            LEFT JOIN journal_details ON journal_details.coa_code = coa.`code`
                            LEFT JOIN journals ON journals.voucher_no = journal_details.voucher_no
                            
                            Where coa.code like '1%' AND coa.description = 'Detail'  
                            AND journals.posting_status = 1
                            AND journals.transaction_date <= :endDate1
                            
                            GROUP BY  coa.code, coa.Name
                    )qry
                    RIGHT JOIN coa
                    ON qry.code = coa.`code`
                    Where coa.code like '1%' AND coa.description = 'Detail'

                    UNION ALL
                    -- Passiva UTANG

                    SELECT
                    coa.type,
                    CASE SUBSTR(coa.`code` ,1,5)
                    WHEN  '20.01' THEN
                        'UTANG LANCAR'
                    WHEN '20.02' THEN
                        'UTANG JK.PANJANG' END AS header,
                    coa.code,		
                    coa.`name`,
                    coa.beginning_balance + IFNULL(qry.Total,0) as balance
                    FROM
                    (	SELECT 
                            coa.code,
                            coa.name,
                            IFNULL(SUM(journal_details.debit),0) AS Debit, 
                            IFNULL(SUM(journal_details.kredit),0) AS Credit,
                            IFNULL(SUM(journal_details.kredit),0) - (IFNULL(SUM(journal_details.debit),0))  AS Total
                            FROM coa
                            LEFT JOIN journal_details ON journal_details.coa_code = coa.`code`
                            LEFT JOIN journals ON journals.voucher_no = journal_details.voucher_no
                            
                            Where coa.code like '2%' AND coa.description = 'Detail'  
                            AND journals.posting_status = 1
                            AND journals.transaction_date <= :endDate2
                            
                            GROUP BY  coa.code, coa.Name
                    )qry
                    RIGHT JOIN coa
                    ON qry.code = coa.`code`
                    Where coa.code like '2%' AND coa.description = 'Detail'


                    UNION ALL

                    -- Passiva EKUITAS MODAL

                    SELECT
                    'Passiva' as type ,	
                    'EKUITAS' as header,
                    coa.code,		
                    coa.`name`,
                    coa.beginning_balance + IFNULL(qry.Total,0) as balance
                    FROM
                    (	SELECT 
                            coa.code,
                            coa.name,
                            IFNULL(SUM(journal_details.debit),0) AS Debit, 
                            IFNULL(SUM(journal_details.kredit),0) AS Credit,
                            IFNULL(SUM(journal_details.kredit),0) - (IFNULL(SUM(journal_details.debit),0))  AS Total
                            FROM coa
                            LEFT JOIN journal_details ON journal_details.coa_code = coa.`code`
                            LEFT JOIN journals ON journals.voucher_no = journal_details.voucher_no
                            
                            Where coa.code like '30.01%' AND coa.description = 'Detail'  
                            AND journals.posting_status = 1
                            AND journals.transaction_date <= :endDate3
                            
                            GROUP BY  coa.code, coa.Name
                    )qry
                    RIGHT JOIN coa
                    ON qry.code = coa.`code`
                    Where coa.code like '30.01%' AND coa.description = 'Detail'

                    UNION ALL
                    -- Passiva EKUITAS Laba Ditahan
                    SELECT
                    'Passiva' as type ,	
                    'EKUITAS' as header,
                    '30.02.01' as code,
                    'Laba Ditahan' as name,
                    sum(saldolabaditahan.balance_amount) as balance
                    from
                    (
                            -- akumulasi laba/rugi (pendapatan - beban) sd akhir bulan
                            SELECT 
                            coa.code ,
                            sum(jd.kredit -jd.debit) as balance_amount 
                            FROM
                            coa
                            INNER JOIN journal_details jd
                            ON coa.`code` = jd.coa_code
                            INNER JOIN journals j 
                            ON jd.voucher_no = j.voucher_no
                            where SUBSTR(coa.`code`,1,1) NOT IN (1,2,3) AND
                            coa.description ='Detail' AND
                            j.transaction_date <= :endDate4 AND
                            j.posting_status = 1

                            GROUP BY 
                            coa.code 
                            
                            UNION ALL
                            
                            -- saldo laba ditahan transaksi + saldo awal master coa
                            SELECT
                            saldotransaksilabaditahan.code,
                            saldotransaksilabaditahan.beginning_balance + saldotransaksilabaditahan.balance as balance_amount
                            from
                            (
                                    
                                    SELECT
                                    -- saldo awal master coa labaditahan
                                    coa.`code`,
                                    coa.beginning_balance,
                                    SUM(IFNULL(qry5.balance_amount, 0)) as balance
                                    from coa
                                    LEFT JOIN
                                    (
                                            SELECT 
                                                    -- transaksi jurnal labaditahan
                                                    jd.coa_code,
                                                    jd.kredit - jd.debit as balance_amount
                                                    
                                                    from 
                                                    journals j INNER JOIN journal_details jd 
                                                    on j.voucher_no = jd.voucher_no
                                                    INNER JOIN coa 
                                                    on jd.coa_code = coa.`code`
                                                    where j.posting_status = 1 AND
                                                    j.transaction_date <= :endDate5 AND
                                                    jd.coa_code = '30.02.01' -- labaditahancode   
                                                    GROUP BY jd.coa_code, jd.kredit - jd.debit

                                    ) qry5
                                                    
                                    on coa.`code` =  qry5.coa_code
                                    Where coa.code = '30.02.01' AND coa.description = 'Detail'
                                    GROUP BY coa.`code`, coa.beginning_balance
                                                                    
                            ) saldotransaksilabaditahan			
                                                            
                                                            
                    ) saldolabaditahan







                " , [
                    'endDate1' => $endDate,
                    'endDate2' => $endDate,
                    'endDate3' => $endDate,
                    'endDate4' => $endDate,
                    'endDate5' => $endDate,
                ]
            
                );
    
                $grouped = [];
    
                foreach ($result as $account) {
                    $grouped[$account->type][$account->header][] = $account;
                }

                $data = [
                    'dataCoa' => $grouped,
                    'lastDate' => Carbon::parse($endDate)->format("d/m/Y")
                ];

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A3', 'potrait');
                $pdf->loadview("admin.accounting.prints.printbalancesheet", $data);
                return $pdf->stream("BalanceSheet(S.d$endDate).pdf", array("Attachment" => false));
    
        } catch (\Throwable $th) {
            abort(400);
        }
    }

    public function printequityChangeReport(string $startDate, string $endDate){

        
        $result = DB::select(
            "
            -- Modal Awal
            SELECT
            'Modal' as type, 
            querymodal.`code`,
            coa.name ,
            coa.name As description,
            querymodal.beginning_balance + querymodal.balance as balance
            from
            (
                SELECT
                coa.`code`,
                coa.beginning_balance,
                SUM(IFNULL(qry3.balance_amount, 0)) as balance
                from coa
                LEFT JOIN
                (
                        SELECT 
                        -- Transaksi jurnal akun modal
                        jd.coa_code,
                        (jd.kredit - jd.debit) as balance_amount
                        
                        from 
                        journals j INNER JOIN journal_details jd 
                        on j.voucher_no = jd.voucher_no
                        INNER JOIN coa 
                        on jd.coa_code = coa.`code`
                        where j.posting_status = 1 AND
                        j.transaction_date < ? AND
                        SUBSTRING(coa.code,1,5) = '30.01'
                        
                        GROUP BY jd.coa_code , jd.kredit - jd.debit
                                                        
                ) qry3
                on coa.`code` =  qry3.coa_code

                Where SUBSTRING(coa.code,1,5) = '30.01' AND coa.description = 'Detail'
                GROUP BY coa.`code`, coa.beginning_balance
                                                                                                                                        
            ) querymodal
                                                                                                                                        
            INNER JOIN 
            coa ON coa.code = querymodal.code

            UNION ALL

            -- Laba DitahanAwalBulan
            SELECT
            'LABA RUGI' as type ,	
            '30.02.01' as code,
            'Laba Ditahan' as name,
            'Saldo Laba Ditahan Awal Bulan' as description,
            sum(saldolabaditahan.balance_amount) as balance
            from
            (
                -- akumulasi laba/rugi (pendapatan - beban) sd akhir bulan
                SELECT 
                coa.code ,
                sum(jd.kredit -jd.debit) as balance_amount 
                FROM
                coa
                INNER JOIN journal_details jd
                ON coa.`code` = jd.coa_code
                INNER JOIN journals j 
                ON jd.voucher_no = j.voucher_no
                where SUBSTR(coa.`code`,1,1) NOT IN (1,2,3) AND
                coa.description ='Detail' AND
                j.transaction_date < ? AND
                j.posting_status = 1

                GROUP BY 
                coa.code 
                
                UNION ALL
                
                -- saldo laba ditahan transaksi + saldo awal master coa
                SELECT
                saldotransaksilabaditahan.code,
                saldotransaksilabaditahan.beginning_balance + saldotransaksilabaditahan.balance as balance_amount
                from
                (
                        
                        SELECT
                        -- saldo awal master coa labaditahan
                        coa.`code`,
                        coa.beginning_balance,
                        SUM(IFNULL(qry5.balance_amount, 0)) as balance
                        from coa
                        LEFT JOIN
                        (
                                SELECT 
                                        -- transaksi jurnal labaditahan
                                        jd.coa_code,
                                        jd.description,
                                        jd.kredit - jd.debit as balance_amount
                                        
                                        from 
                                        journals j INNER JOIN journal_details jd 
                                        on j.voucher_no = jd.voucher_no
                                        INNER JOIN coa 
                                        on jd.coa_code = coa.`code`
                                        where j.posting_status = 1 AND
                                        j.transaction_date < ? AND
                                        jd.coa_code = '30.02.01' -- labaditahancode   
                                        GROUP BY jd.coa_code, jd.kredit - jd.debit, jd.description

                        ) qry5
                                        
                        on coa.`code` =  qry5.coa_code
                        Where coa.code = '30.02.01' AND coa.description = 'Detail'
                        GROUP BY coa.`code`, coa.beginning_balance
                                                        
                ) saldotransaksilabaditahan			
                                                
                                                
            ) saldolabaditahan

            UNION ALL

            -- Laba Bulan ini pendapatan - beban
            SELECT
            'LABA RUGI' as type ,	
            '30.02.03' as code,
            'Laba Bulan Ini' as name,
            'Laba Bulan Ini' as description,
            ifnull(sum(saldolabaditahan.balance_amount),0) as balance
            FROM
            (
            SELECT 
                coa.code ,
                sum(jd.kredit -jd.debit) as balance_amount 
                FROM
                coa
                INNER JOIN journal_details jd
                ON coa.`code` = jd.coa_code
                INNER JOIN journals j 
                ON jd.voucher_no = j.voucher_no
                where SUBSTR(coa.`code`,1,1) NOT IN (1,2,3) AND
                coa.description ='Detail' AND
                j.transaction_date BETWEEN ? AND ? AND
                j.posting_status = 1

                GROUP BY 
                coa.code 
            )saldolabaditahan

            UNION ALL
            -- transaksi labaditahan bulan ini
            SELECT 
            'LABA RUGI' as type, 
            jd.coa_code,
            coa.name,
            jd.description,
            jd.kredit - jd.debit as balance_amount

            from 
            journals j INNER JOIN journal_details jd 
            on j.voucher_no = jd.voucher_no
            INNER JOIN coa 
            on jd.coa_code = coa.`code`
            where j.posting_status = 1 AND
            j.transaction_date BETWEEN ? AND ? AND
            jd.coa_code = '30.02.01' -- labaditahancode   
            GROUP BY jd.coa_code,coa.name, jd.kredit - jd.debit, jd.description

            " , [$startDate,$startDate,$startDate, $startDate, $endDate, $startDate, $endDate  ]
        
            );

        
            $groupedData = collect($result)->groupBy('type');
    
            $data = [
                'coaData' => $groupedData,
                'firstDate' => Carbon::parse($startDate)->format("d/m/Y"),
                'lastDate' => Carbon::parse($endDate)->format("d/m/Y")
            ];
        
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'potrait');
            $pdf->loadview("admin.accounting.prints.printcapitalchange",$data);
            return $pdf->stream("CapitalChange($startDate-$endDate).pdf", array("Attachment" => false));
        try {
            //code...
    
        } catch (\Throwable $th) {
            abort(400);
        }
    }

    public function printjurnalrealisasi($id){
        
        try {
            //code...
            $journal = Journal::join('journal_details', "journals.voucher_no", "=", "journal_details.voucher_no")
            ->join("coa", "journal_details.coa_code", "=", "coa.code")
            ->where("journals.ref_no", $id)
            ->select("coa.name", "journals.*", "journal_details.*")
            ->orderBy("id", 'asc')
            ->get();

            if (count($journal) == 0){
                throw new Exception();
            }

            $realisasi = ProjectRealisation::join("projects", "projects.code" , "=", "project_realisations.project_code")
                        ->join('customers', 'customers.code', "=",'projects.customer_code')
            ->select('project_realisations.*','projects.name as project_name', DB::raw('ifnull(customers.name ,"-" ) as customer_name'))
            ->where("project_realisations.code", $id)->get();

            $data = [
                "realisasiData" => $realisasi,
                "journal" => $journal,
            ];

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadview("admin.project.print.printjournalrealisasi", $data);
            return $pdf->stream("JournalRealisasi-$id.pdf", array("Attachment" => false));

        } catch (\Throwable $th) {
            abort(404);
        }
    }
    public function printprojectrealisationdetail($id){


        // $Purchase = Purchase::join("purchase_details", "purchases.purchase_no" , "=", "purchase_details.purchase_no")
        // ->join("suppliers", "purchases.supplier_code" , "=", "suppliers.code")
        // ->leftJoin("items", 'items.code', "=", "purchase_details.item_code")
        // ->select('purchases.*', 'purchases.total as total_item_purchase','purchases.created_by as dibuat_oleh','items.name as item_name',  DB::raw('ifnull(suppliers.name ,"-" ) as supplier_name'),  DB::raw('ifnull(suppliers.address ,"-" ) as supplier_address'), DB::raw('ifnull(suppliers.phone,"-" ) as supplier_phone'),"purchase_details.*")
        // ->where("purchases.purchase_no", $id)->get();

        $projectRealisation =  ProjectRealisation::join('customers', 'customers.code', '=', 'project_realisations.customer_code')
                                ->join("projects", 'projects.code', '=', 'project_realisations.project_code')
                                ->select(
                                "projects.code as project_code", 
                                "project_realisations.code as project_realisation_code",
                                "project_realisations.realisation_date",
                                "projects.budget as project_amount",
                                "project_realisations.realisation_amount as realisation_amount",
                                "projects.name as project_name", 
                                "customers.name as customer_name",
                                "customers.code as customer_code",
                                'project_realisations.termin',
                                'projects.total_termin')
                                ->where('project_realisations.code', $id)
                                ->first();
        
        $dataBahanBaku = Project_Detail_Realisations::join("items", "project_detail_realisations.item_code", '=', 'items.code')
                        ->where('project_detail_realisations.project_realisation_code', $id)
                        ->get();
        $dataUpah = Project_Detail_B_Realisation::join("upah", 'project_detail_b_realisations.upah_code', '=', 'upah.code')
                    ->select('upah.job as upah_name','project_detail_b_realisations.*' )
                    ->where("project_detail_b_realisations.project_realisation_code" ,$id)
                    ->get();
        
        
        $data['projectRealisation'] = $projectRealisation;
        $data['dataBahanBaku'] = $dataBahanBaku;
        $data['dataUpah'] = $dataUpah;
        

        if (!$projectRealisation){
        abort(404);
        };
        // $data = [
        // "dataPurchase" => $Purchase
        // ];
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'potrait');
        $pdf->loadview("admin.project.print.printrealisasi", $data);
        return $pdf->stream("ProjectRealisation-($id).pdf", array("Attachment" => false));
    }
}
