<?php

namespace App\Http\Controllers;

use App\Models\Advanced_Receipt;
use App\Models\CashBook;
use App\Models\Item;
use App\Models\Journal;
use App\Models\Payment;
use App\Models\Project;
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
        ->get();

        $journalFinish = Journal::join("journal_details", "journals.voucher_no", "=", "journal_details.voucher_no")
        ->join("coa", "journal_details.coa_code", "=", "coa.code")
        ->select("coa.name as coa_name", "journals.*", "journal_details.*")
        ->where("journals.ref_no", $id)
        ->where("journals.journal_type_code", "!=", "jp")
        ->where("journal_details.description", "like", "%Realisasi%")
        ->get();

        $journalPenyesuaian = Journal::join("journal_details", "journals.voucher_no", "=", "journal_details.voucher_no")
        ->join("coa", "journal_details.coa_code", "=", "coa.code")
        ->select("coa.name as coa_name", "journals.*", "journal_details.*")
        ->where("journals.ref_no", $id)
        ->where("journals.journal_type_code", "jp")
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

        $totalDebitRealisasi = 0;
        $totalKreditRealisasi = 0;

        foreach($journalFinish as $j){
            $totalDebitRealisasi +=  floatval($j->debit);
            $totalKreditRealisasi  += floatval($j->kredit);
        }

        $totalDebitPenyesuaian = 0;
        $totalKreditPenyesuian = 0;
        foreach($journalPenyesuaian as $j){
            $totalDebitPenyesuaian +=  floatval($j->debit);
            $totalKreditPenyesuian += floatval($j->kredit);
        }

        $data =[
            "dataprojectdanjurnal" => $journal,
            "jurnalpenyesuaian" => $journalPenyesuaian,
            "jurnalRealisasi" =>  $journalFinish,
            "totalDebit" => $totalDebit,
            "totalKredit" => $totalKredit,
            "totalDebitPenyesuaian" => $totalDebitPenyesuaian,
            "totalKreditPenyesuian" => $totalKreditPenyesuian,
            "totalDebitRealisasi" => $totalDebitRealisasi ,
            "totalKreditRealisasi" => $totalKreditRealisasi ,
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

        $project_detail = new ProjectDetailController();
        $project_detail = $project_detail->getDetail($id);
        $dataBahanBaku = $project_detail->get();
        

        $project_detail_b = new ProjectDetailBController();
        $project_detail_b = $project_detail_b->getDetailB($id);
        $dataUpah = $project_detail_b->get();

        // Realisation

        $project_detail_realisation = new ProjectDetailRealisationController();
        $project_detail_realisation = $project_detail_realisation->getDetail($id);
        $dataBahanBakuRealisation = $project_detail_realisation->get();

        $project_detail_realisationdiff = new ProjectDetailRealisationController();
        $project_detail_realisationdiff = $project_detail_realisationdiff->getDetailDifference($id);
        $dataBahanBakuRealisationdiff = $project_detail_realisationdiff->get();

        $project_detail_realisation_b = new ProjectDetailRealisationBController();
        $project_detail_realisation_b = $project_detail_realisation_b->getDetailB($id);
        $dataUpahRealisation = $project_detail_realisation_b->get();

        $project_detail_realisation_bdiff = new ProjectDetailRealisationBController();
        $project_detail_realisation_bdiff = $project_detail_realisation_bdiff->getDetailDifference($id);
        $dataUpahRealisationdiff = $project_detail_realisation_bdiff->get();

        $totalUpah = 0;
        $totalUpahrealisation = 0;

        foreach ($dataUpah as $upah) {
            $totalUpah+= floatval($upah->total);
        }
        foreach ($dataUpahRealisation as $upah) {
            $totalUpahrealisation+= floatval($upah->total);
        }
        if ($project === null){
            abort(404);
        };


        $data =[
            "project" => $project,
            "dataBahanBaku" =>  $dataBahanBaku,
            "dataBahanBakuRealisation" => $dataBahanBakuRealisation,
            "dataBahanBakuRealisationdiff" => $dataBahanBakuRealisationdiff,
            "dataUpah" => $dataUpah,
            "dataUpahRealisation" => $dataUpahRealisation,
            "dataUpahRealisationdiff" => $dataUpahRealisationdiff,
            "totalUpah" => $totalUpah,
            "totalUpahrealisation" => $totalUpahrealisation
        ];
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'potrait'); 
        $pdf->loadview("admin.project.print.printproject", $data);
        return $pdf->stream("ProjectReport-$id.pdf", array("Attachment" => false));
    }

    public function printprojectrecap($statuscode = null,$startdate,$lastdate, $customercode = null){

        try {
             
        $project = Project::join('type_projects', 'projects.project_type_code', '=', 'type_projects.code')
        ->join('customers','projects.customer_code', '=', 'customers.code' )
        ->select('projects.*', 'type_projects.name as type_project_name', 'type_projects.description as type_project_description', 
        'customers.name as customer_name', 'customers.address as customer_address')
        ->whereBetween('transaction_date' , [$startdate,$lastdate])
        ->when($statuscode !== null , function($query) use($statuscode){
            $query->where("projects.project_status", $statuscode);
        })
        ->when($customercode !== null, function($query) use($customercode){
            $query->where("projects.customer_code", $customercode);
        })
        ->get();

        if ($project === null){
            abort(404);
        };
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
        ->select('stocks.item_date', 'stocks.ref_no' , DB::raw("'Pembelian' As keterangan"),
        'stocks.item_code' , "items.name as item_name", 'stocks.unit_code' , 'stocks.actual_stock as qty' , 'stocks.cogs',DB::raw('stocks.actual_stock * stocks.cogs as total_cogs'))
        ->where("stocks.item_code", $itemCode)
        ->whereBetween('stocks.item_date', [$startDate, $lastDate]);
        

        $ITEMOUT = Stocks_Out::join("items", "items.code", "=", "stocks_out.item_code")
        ->select('stocks_out.item_date','stocks_out.ref_no' , DB::raw("'Pemakaian Proyek' As keterangan"),
        'stocks_out.item_code' , "items.name as item_name", 'stocks_out.unit_code', 'stocks_out.qty', 'stocks_out.cogs' , DB::raw('stocks_out.qty * stocks_out.cogs as total_cogs'))
        ->where("stocks_out.item_code", $itemCode)
        ->whereBetween("stocks_out.item_date",  [$startDate, $lastDate]);


        $UNIONALLRESULT = $ITEMIN->UnionALL($ITEMOUT)
                        ->orderBy('item_date', 'asc')->get();
    

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
}
