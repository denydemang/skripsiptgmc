<?php

namespace App\Http\Controllers;

use App\Models\Advanced_Receipt;
use App\Models\Cash_Receive;
use App\Models\CashBook;
use App\Models\CashBook_Detail;
use App\Models\CashBook_DetailB;
use App\Models\Invoice;
use App\Models\Journal;
use App\Models\Journal_Detail;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Project_Detail;
use App\Models\Project_Detail_B_Realisation;
use App\Models\Project_Detail_Realisations;
use App\Models\ProjectDetailB;
use App\Models\Purchase;
use App\Models\Purchase_Detail;
use App\Models\Stock;
use App\Models\Stocks_Out;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountingController extends AdminController
{
    public function journalstartproject($ref_no, $type_journal, $project){

        $supplyModel = Journal::where("voucher_no", 'like', "%".$type_journal."%")->orderBy("voucher_no", "desc")->lockForUpdate()->first();
        $AutomaticCode = $this->automaticCode($type_journal, $supplyModel,true,"voucher_no");
        $Variation_COA_ProjectDetailB = ProjectDetailB::join("upah", "project_detail_b.upah_code", "=", "upah.code")
        ->select("upah.coa_code" , DB::raw("sum(total) as totalcogs"))
        ->where("project_detail_b.project_code", $ref_no)
        ->groupBy("upah.coa_code")
        ->get();
        $Variation_COA_ProjectDetail = Project_Detail::join("items",'project_details.item_code', '=', 'items.code')
        ->join("categories", "items.category_code", "=", 'categories.code')
        ->join("stocks_out" , function ($join){
            $join->on("stocks_out.item_code", "=", "items.code")
            ->on("stocks_out.ref_no", "=", "project_details.project_code");
        })
        ->select("categories.coa_code",DB::raw("SUM(stocks_out.qty * stocks_out.cogs) as totalcogs"))
        ->where("project_details.project_code", $ref_no)
        ->groupBy("categories.coa_code")
        ->get();

        // Insert Header Journal
        $journal = New Journal();
        $journal->voucher_no = $AutomaticCode;
        $journal->transaction_date = Carbon::now();
        $journal->ref_no = $ref_no;
        $journal->journal_type_code = "JU";
        $journal->posting_status = 0;
        $journal->created_by =Auth::user()->username;
        $journal->save();

        // // Insert Beban Material Jurnal Detail
        // $cogsMaterial = Stocks_Out::where("ref_no", $ref_no)->selectRaw('SUM(qty * cogs) as total')->first();
        // $cogsMaterial = floatval($cogsMaterial->total);

        // $journalDetail  = New Journal_Detail();
        // $journalDetail->voucher_no = $journal->voucher_no;
        // $journalDetail->description = "Beban Material Untuk Pengerjaan Project Code : ". $project->code ;
        // $journalDetail->coa_code = $project->coa_expense;
        // $journalDetail->debit = $cogsMaterial;
        // $journalDetail->kredit = 0;
        // $journalDetail->created_by = Auth::user()->username;
        // $journalDetail->save();

        // Insert Proyek Dalam Proses Journal Detail
        $cogsMaterial = Stocks_Out::where("ref_no", $ref_no)->selectRaw('SUM(qty * cogs) as total')->first();
        $cogsMaterial = floatval($cogsMaterial->total);
        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description = "Proyek Dalam Proses Untuk Pengerjaan Project Code : ". $project->code ;
        $journalDetail->coa_code = "10.01.04.02";
        $journalDetail->debit = $cogsMaterial;
        $journalDetail->kredit = 0;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();

        // Insert Persediaan Keluar Jurnal Detail
        foreach ($Variation_COA_ProjectDetail as $coa){
            $journalDetail  = New Journal_Detail();
            $journalDetail->voucher_no = $journal->voucher_no;
            $journalDetail->description = "Inventory Out Untuk Pengerjaan Project Code: $project->code"  ;
            $journalDetail->coa_code = $coa->coa_code;
            $journalDetail->debit = 0;
            $journalDetail->kredit = floatval($coa->totalcogs);
            $journalDetail->created_by = Auth::user()->username;
            $journalDetail->save();
        }

        // // Insert  Beban TKL Journal Detail
        // $totalutanggajitkl = 0;
        // foreach ($Variation_COA_ProjectDetailB as $coa){
        //     $journalDetail  = New Journal_Detail();
        //     $totalutanggajitkl+= floatval($coa->totalcogs);
        //     $journalDetail->voucher_no = $journal->voucher_no;
        //     $journalDetail->description = "Beban Tenaga Kerja Langung Untuk Pengerjaan Project Code: $project->code"  ;
        //     $journalDetail->coa_code = $coa->coa_code;
        //     $journalDetail->debit = floatval($coa->totalcogs);
        //     $journalDetail->kredit = 0;
        //     $journalDetail->created_by = Auth::user()->username;
        //     $journalDetail->save();
        // }


         $totalutanggajitkl = 0;
        foreach ($Variation_COA_ProjectDetailB as $coa){
            $totalutanggajitkl+= floatval($coa->totalcogs);
        }
       // Insert Proyek Dalam Proses Journal Detail
        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description = "Proyek Dalam Proses Untuk Pengerjaan Project Code: $project->code"  ;
        $journalDetail->coa_code = "10.01.04.02";
        $journalDetail->debit =  $totalutanggajitkl;
        $journalDetail->kredit = 0;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();
        // Insert Utang Gaji TKL
        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description = "Utang Gaji TKL Untuk Pengerjaan Project Code : ". $project->code ;
        $journalDetail->coa_code = $project->coa_payable;
        $journalDetail->debit = 0;
        $journalDetail->kredit = $totalutanggajitkl;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();
    }

    public function journalFinishProyek($ref_no, $type_journal, $project, $transDate){
        $supplyModel = Journal::where("voucher_no", 'like', "%".$type_journal."%")->orderBy("voucher_no", "desc")->lockForUpdate()->first();
        $AutomaticCode = $this->automaticCode($type_journal, $supplyModel,true,"voucher_no");
        $Variation_COA_ProjectDetailB = ProjectDetailB::join("upah", "project_detail_b.upah_code", "=", "upah.code")
        ->select("upah.coa_code" , DB::raw("sum(total) as totalcogs"))
        ->where("project_detail_b.project_code", $ref_no)
        ->groupBy("upah.coa_code")
        ->get();

        // Insert Header Journal
        $journal = New Journal();
        $journal->voucher_no = $AutomaticCode;
        $journal->transaction_date =  $transDate;
        $journal->ref_no = $ref_no;
        $journal->journal_type_code = "JU";
        $journal->posting_status = 0;
        $journal->created_by =Auth::user()->username;
        $journal->save();

        $cogsMaterial = Stocks_Out::where("ref_no", $ref_no)->selectRaw('SUM(qty * cogs) as total')->first();
        $cogsMaterial = floatval($cogsMaterial->total);

        // Insert Beban Material Jurnal Detail
        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description = "Beban Material Untuk Realisasi Pengerjaan Project Code : ". $project->code ;
        $journalDetail->coa_code = $project->coa_expense;
        $journalDetail->debit = $cogsMaterial;
        $journalDetail->kredit = 0;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();

        // Insert Proyek Dalam Proses Journal Detail
        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description = "Proyek Dalam Proses Untuk Realisasi Pengerjaan Project Code : ". $project->code ;
        $journalDetail->coa_code = "10.01.04.02";
        $journalDetail->debit = 0 ;
        $journalDetail->kredit = $cogsMaterial;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();


        //  Insert Beban BTKL Journal Detail
        $totalutanggajitkl = 0;
        foreach ($Variation_COA_ProjectDetailB as $coa){
            $journalDetail  = New Journal_Detail();
            $totalutanggajitkl+= floatval($coa->totalcogs);
            $journalDetail->voucher_no = $journal->voucher_no;
            $journalDetail->description = "Beban TKL Untuk Realisasi Pengerjaan Project Code: $project->code"  ;
            $journalDetail->coa_code = $coa->coa_code;
            $journalDetail->debit = floatval($coa->totalcogs);
            $journalDetail->kredit = 0;
            $journalDetail->created_by = Auth::user()->username;
            $journalDetail->save();
        }

        // Insert Proyek Dalam Proses Journal Detail
        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description = "Proyek Dalam Proses Untuk Realisasi Pengerjaan Project Code : ". $project->code ;
        $journalDetail->coa_code =  "10.01.04.02" ;
        $journalDetail->debit = 0;
        $journalDetail->kredit = $totalutanggajitkl;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();
    }

    public function journalPenyesuaianRealisationProyek($codeProyek, $transDate){

        $supplyModel = Journal::where("voucher_no", 'like', "%JP%")->orderBy("voucher_no", "desc")->lockForUpdate()->first();
        $AutomaticCode = $this->automaticCode("JP", $supplyModel,true,"voucher_no");
        $project = Project::where("code", $codeProyek)->first();

        // HEADER JOURNAL

        // Insert Header Journal
        $journal = New Journal();
        $journal->voucher_no = $AutomaticCode;
        $journal->transaction_date = $transDate;
        $journal->ref_no = $codeProyek;
        $journal->journal_type_code = "JP";
        $journal->posting_status = 0;
        $journal->created_by =Auth::user()->username;
        $journal->save();

        // Journal Penyesuaian Material Yang Sisa (INVENTORY IN)
        // ===============================================================
        $getTotalCogsExpense = Stock::where("ref_no", $codeProyek.'-Realisation')->selectRaw('IFNULL(SUM(actual_stock * cogs), 0) as totalcogs')->first();
        $getTotalCogsExpense = floatval($getTotalCogsExpense->totalcogs);
        if ($getTotalCogsExpense > 0 ){

        $Variation_COA_Detail_Realisations_IN = Project_Detail_Realisations::join("items",'project_detail_realisations.item_code', '=', 'items.code')
        ->join("categories", "items.category_code", "=", 'categories.code')
        ->join("stocks" , "stocks.item_code", "=", "items.code")
        ->select("categories.coa_code",DB::raw("SUM(stocks.actual_stock * stocks.cogs) as totalcogs"))
        ->where("project_detail_realisations.project_code" , $codeProyek)
        ->where("stocks.ref_no", $codeProyek.'-Realisation')
        ->groupBy("categories.coa_code")
        ->get();

        // Insert Persediaan Masuk (material sisa) Jurnal Detail
        foreach ($Variation_COA_Detail_Realisations_IN as $coa){
            $journalDetail  = New Journal_Detail();
            $journalDetail->voucher_no = $journal->voucher_no;
            $journalDetail->description = "Inventory IN Penyesuaian Material Sisa Pada Realisasi Pengerjaan Project Code: $project->code"  ;
            $journalDetail->coa_code = $coa->coa_code;
            $journalDetail->debit = floatval($coa->totalcogs);
            $journalDetail->kredit = 0;
            $journalDetail->created_by = Auth::user()->username;
            $journalDetail->save();
        }

        // Insert Penyesuaian Beban Material Journal Detail
        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description = "Penyesuaian Beban Material Pada Realisasi Pengerjaan Project Code : $project->code"  ;
        $journalDetail->coa_code = $project->coa_expense;
        $journalDetail->debit = 0;
        $journalDetail->kredit =$getTotalCogsExpense ;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();

        }
        // =================================================================


        // Journal Penyesuaian Material Yang Kurang (INVENTORY OUT)
        // ===============================================================
        $getTotalCogsExpense = Stocks_Out::where("ref_no", $codeProyek.'-Realisation')->selectRaw('IFNULL(SUM(qty * cogs), 0) as totalcogs')->first();
        $getTotalCogsExpense = floatval($getTotalCogsExpense->totalcogs);
        if ($getTotalCogsExpense > 0 ){
            $Variation_COA_Detail_Realisations_OUT = Project_Detail_Realisations::join("items",'project_detail_realisations.item_code', '=', 'items.code')
            ->join("categories", "items.category_code", "=", 'categories.code')
            ->join("stocks_out" , "stocks_out.item_code", "=", "items.code")
            ->select("categories.coa_code",DB::raw("SUM(stocks_out.qty * stocks_out.cogs) as totalcogs"))
            ->where("project_detail_realisations.project_code", $codeProyek)
            ->where("stocks_out.ref_no", $codeProyek.'-Realisation')
            ->groupBy("categories.coa_code")
            ->get();

             // Insert Penyesuaian Beban Material Journal Detail
            $journalDetail  = New Journal_Detail();
            $journalDetail->voucher_no = $journal->voucher_no;
            $journalDetail->description = "Penyesuaian Beban Material Pada Realisasi Pengerjaan Project Code : $project->code"  ;
            $journalDetail->coa_code = $project->coa_expense;
            $journalDetail->debit = $getTotalCogsExpense;
            $journalDetail->kredit = 0;
            $journalDetail->created_by = Auth::user()->username;
            $journalDetail->save();

            // Insert Persediaan Keluar (material Kurang) Jurnal Detail
            foreach ($Variation_COA_Detail_Realisations_OUT as $coa){
                $journalDetail  = New Journal_Detail();
                $journalDetail->voucher_no = $journal->voucher_no;
                $journalDetail->description = "Inventory OUT Penyesuaian Material Yg Kurang Pada Realisasi Pengerjaan Project Code: $project->code"  ;
                $journalDetail->coa_code = $coa->coa_code;
                $journalDetail->debit = 0;
                $journalDetail->kredit = floatval($coa->totalcogs);
                $journalDetail->created_by = Auth::user()->username;
                $journalDetail->save();
            }
        }


        // ===============================================================


        // Journal Penyesuaian Upah
        // ================================================================
            $PRB = Project_Detail_B_Realisation::join("upah", "project_detail_b_realisations.upah_code", "=", "upah.code")
            ->select("upah.coa_code",
            DB::raw("ifnull(sum(ifnull(project_detail_b_realisations.qty_estimated - project_detail_b_realisations.qty_used, 0) * project_detail_b_realisations.price), 0) as totalCogs"))
            ->where("project_detail_b_realisations.project_code", $codeProyek)
            ->whereColumn("project_detail_b_realisations.qty_estimated", "!=" ,"project_detail_b_realisations.qty_used")
            ->groupBy("upah.coa_code")
            ->get();
            if (floatval($PRB[0]['totalCogs']) != 0){
                foreach($PRB as $p){
                    if (floatval($p->totalCogs) > 0){ //Estimasi Lebih Besar dari Yang Sesungguhnya Berati Mengurangi Beban BTKL(kredit)
                        $journalDetail  = New Journal_Detail();
                        $journalDetail->voucher_no = $journal->voucher_no;
                        $journalDetail->description = "Penyesuaian Utang BTKL Pada Realisasi Pengerjaan Project Code: $project->code"  ;
                        $journalDetail->coa_code = $project->coa_payable;
                        $journalDetail->debit = floatval($p->totalCogs) ;
                        $journalDetail->kredit = 0;
                        $journalDetail->created_by = Auth::user()->username;
                        $journalDetail->save();

                        $journalDetail  = New Journal_Detail();
                        $journalDetail->voucher_no = $journal->voucher_no;
                        $journalDetail->description = "Penyesuaian Beban BTKL Pada Realisasi Pengerjaan Project Code: $project->code"  ;
                        $journalDetail->coa_code = $p->coa_code;
                        $journalDetail->debit =  0;
                        $journalDetail->kredit = floatval($p->totalCogs);
                        $journalDetail->created_by = Auth::user()->username;
                        $journalDetail->save();

                    } else{ //Estimasi Lebih Kecil dari Yang Sesungguhnya Berati Menambah Beban BTKL(debit)

                        $journalDetail  = New Journal_Detail();
                        $journalDetail->voucher_no = $journal->voucher_no;
                        $journalDetail->description = "Penyesuaian Beban BTKL Pada Realisasi Pengerjaan Project Code: $project->code"  ;
                        $journalDetail->coa_code = $p->coa_code;
                        $journalDetail->debit =  -1 * floatval($p->totalCogs);
                        $journalDetail->kredit = 0;
                        $journalDetail->created_by = Auth::user()->username;
                        $journalDetail->save();

                        $journalDetail  = New Journal_Detail();
                        $journalDetail->voucher_no = $journal->voucher_no;
                        $journalDetail->description = "Penyesuaian Utang BTKL Pada Realisasi Pengerjaan Project Code: $project->code"  ;
                        $journalDetail->coa_code = $project->coa_payable;
                        $journalDetail->debit = 0;
                        $journalDetail->kredit = -1 * floatval($p->totalCogs) ;
                        $journalDetail->created_by = Auth::user()->username;
                        $journalDetail->save();
                    }
                }
            }


        // ==============================================================




    }

    public function journalPembelian($ref_no){

        $purchase = Purchase::join("suppliers", "purchases.supplier_code" , "=", "suppliers.code")
        ->select('purchases.*', DB::raw('ifnull(suppliers.name ,"-" ) as supplier_name'),  DB::raw('ifnull(suppliers.address ,"-" ) as supplier_address'), DB::raw('ifnull(suppliers.phone,"-" ) as supplier_phone'))
        ->where("purchase_no", $ref_no)->first();

        $Variation_COA_Item = Purchase_Detail::join("items",'purchase_details.item_code', '=', 'items.code')
        ->join("categories", "items.category_code", "=", 'categories.code')
        ->join("stocks" , function ($join){
            $join->on("stocks.item_code", "=", "items.code")
            ->on("stocks.ref_no", "=", "purchase_details.purchase_no");
        })
        ->select("categories.coa_code",DB::raw("SUM(stocks.actual_stock * stocks.cogs) as totalcogs"))
        ->where("purchase_details.purchase_no", $ref_no)
        ->groupBy("categories.coa_code")
        ->get();
        $supplyModel = Journal::where("voucher_no", 'like', "%JPEM%")->orderBy("voucher_no", "desc")->lockForUpdate()->first();
        $AutomaticCode = $this->automaticCode("JPEM", $supplyModel,true,"voucher_no");

        // Insert Header Journal
        $journal = New Journal();
        $journal->voucher_no = $AutomaticCode;
        $journal->transaction_date = $purchase->transaction_date;
        $journal->ref_no = $purchase->purchase_no;
        $journal->journal_type_code = "JPEM";
        $journal->posting_status = 0;
        $journal->created_by =Auth::user()->username;
        $journal->save();

        $totalUtang = 0;
        // Insert Detail Journal
        // Insert Persediaan Masuk Jurnal Detail
        foreach ($Variation_COA_Item as $coa){

            $totalUtang += round(floatval($coa->totalcogs), 2);
            $journalDetail  = New Journal_Detail();
            $journalDetail->voucher_no = $journal->voucher_no;
            $journalDetail->description = "Inventory IN Untuk Trans Pembelian: $purchase->purchase_no"  ;
            $journalDetail->coa_code = $coa->coa_code;
            $journalDetail->debit = round(floatval($coa->totalcogs), 2);
            $journalDetail->kredit = 0;
            $journalDetail->created_by = Auth::user()->username;
            $journalDetail->save();
        }

        // PPN
        if (floatval($purchase->percen_ppn) > 0)
        {
            $totalUtang += round(floatval($purchase->ppn_amount), 2);
            $journalDetail  = New Journal_Detail();
            $journalDetail->voucher_no = $journal->voucher_no;
            $journalDetail->description = "PPN Masukan Untuk Pembelian: $purchase->purchase_no"  ;
            $journalDetail->coa_code = '10.01.03.03';
            $journalDetail->debit = round(floatval($purchase->ppn_amount),2);
            $journalDetail->kredit = 0;
            $journalDetail->created_by = Auth::user()->username;
            $journalDetail->save();
        }

         // UTANG  USAHA

        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description = "Utang Usaha Pada Supplier : $purchase->supplier_name($purchase->supplier_code), Pembelian :  $purchase->purchase_no "  ;
        $journalDetail->coa_code = '20.01.01.01';
        $journalDetail->debit = 0;
        $journalDetail->kredit = $totalUtang;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();


    }

    public function journalPembayaran($code){

        $payment = Payment::join("suppliers", "payments.supplier_code" , "=", "suppliers.code")
        ->select('payments.*', DB::raw('ifnull(suppliers.name ,"-" ) as supplier_name'),  DB::raw('ifnull(suppliers.address ,"-" ) as supplier_address'), DB::raw('ifnull(suppliers.phone,"-" ) as supplier_phone'))
        ->where('bkk_no', $code)
        ->first();



        $supplyModel = Journal::where("voucher_no", 'like', "%JKK%")->orderBy("voucher_no", "desc")->lockForUpdate()->first();
        $AutomaticCode = $this->automaticCode("JKK", $supplyModel,true,"voucher_no");

        // Insert Header Journal
        $journal = New Journal();
        $journal->voucher_no = $AutomaticCode;
        $journal->transaction_date = $payment->transaction_date;
        $journal->ref_no = $payment->bkk_no;
        $journal->journal_type_code = "JKK";
        $journal->posting_status = 0;
        $journal->created_by =Auth::user()->username;
        $journal->save();

        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description = "Pembayaran Hutang Usaha Kode Pembayaran : $payment->bkk_no ; Supplier:  $payment->supplier_name ($payment->supplier_code)"  ;
        $journalDetail->coa_code ="20.01.01.01";
        $journalDetail->debit = round(floatval($payment->total_amount), 2);
        $journalDetail->kredit = 0;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();

        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description = "Kas/Bank Keluar Kode Pembayaran : $payment->bkk_no ; Supplier:  $payment->supplier_name ($payment->supplier_code)";
        $journalDetail->coa_code = $payment->coa_cash_code;
        $journalDetail->debit = 0;
        $journalDetail->kredit = round(floatval($payment->total_amount), 2);
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();

    }

    public function journalCashBook($code){

        $Cashbook = CashBook::where("cash_no", $code)->first();
        $Cashbook_detail = CashBook_Detail::where("cash_no", $code)->first();
        $Cashbook_detail_b = CashBook_DetailB::where("cash_no", $code)->first();

        $AutomaticCode= "";

        if($Cashbook->CbpType == "P"){
            $supplyModel = Journal::where("voucher_no", 'like', "%JKK%")->orderBy("voucher_no", "desc")->lockForUpdate()->first();
            $AutomaticCode = $this->automaticCode("JKK", $supplyModel,true,"voucher_no");
        } else {
            $supplyModel = Journal::where("voucher_no", 'like', "%JKM%")->orderBy("voucher_no", "desc")->lockForUpdate()->first();
            $AutomaticCode = $this->automaticCode("JKM", $supplyModel,true,"voucher_no");
        }




        // Insert Header Journal
        $journal = New Journal();
        $journal->voucher_no = $AutomaticCode;
        $journal->transaction_date = $Cashbook->transaction_date;
        $journal->ref_no = $Cashbook->cash_no;
        $journal->journal_type_code = $Cashbook->CbpType == "P" ? "JKK" : "JKM";
        $journal->posting_status = 0;
        $journal->created_by =Auth::user()->username;
        $journal->save();


        //Insert Detail Journal
        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description =  $Cashbook->CbpType == "P" ? "$Cashbook_detail->description $Cashbook->cash_no" : "$Cashbook->description $Cashbook->cash_no" ;
        $journalDetail->coa_code =  $Cashbook->CbpType == "P" ?  $Cashbook_detail->coa :  $Cashbook->COA_Cash;
        $journalDetail->debit = $Cashbook->CbpType == "P" ? round(floatval($Cashbook_detail->amount), 2) :  round(floatval($Cashbook->total_transaction), 2)  ;
        $journalDetail->kredit = 0;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();

        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description =  $Cashbook->CbpType == "P" ? "$Cashbook->description $Cashbook->cash_no" : "$Cashbook_detail->description $Cashbook->cash_no" ;
        $journalDetail->coa_code =  $Cashbook->CbpType == "P" ?  $Cashbook->COA_Cash : $Cashbook_detail->coa ;
        $journalDetail->debit =  0 ;
        $journalDetail->kredit = $Cashbook->CbpType == "P" ? round(floatval($Cashbook->total_transaction), 2) : round(floatval($Cashbook_detail->amount), 2) ;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();

        if ($Cashbook_detail_b){
            $journalDetail  = New Journal_Detail();
            $journalDetail->voucher_no = $journal->voucher_no;
            $journalDetail->description = "$Cashbook_detail_b->description $Cashbook->cash_no" ;
            $journalDetail->coa_code =  $Cashbook_detail_b->COA;
            $journalDetail->debit =  round(floatval($Cashbook_detail_b->debit), 2);
            $journalDetail->kredit =  round(floatval($Cashbook_detail_b->credit), 2) ;
            $journalDetail->created_by = Auth::user()->username;
            $journalDetail->save();
        }


    }

    public function journalAdvancedReceipt($code){

        $AR = Advanced_Receipt::where("adr_no", $code)->first();


        $supplyModel = Journal::where("voucher_no", 'like', "%JKM%")->orderBy("voucher_no", "desc")->lockForUpdate()->first();
        $AutomaticCode = $this->automaticCode("JKM", $supplyModel,true,"voucher_no");


        // Insert Header Journal
        $journal = New Journal();
        $journal->voucher_no = $AutomaticCode;
        $journal->transaction_date = $AR->transaction_date;
        $journal->ref_no = $AR->adr_no;
        $journal->journal_type_code = "JKM";
        $journal->posting_status = 0;
        $journal->created_by =Auth::user()->username;
        $journal->save();


        //Insert Detail Journal
        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description =  $AR->description ." ( $AR->adr_no - $AR->customer_code )";
        $journalDetail->coa_code = $AR->coa_debit;
        $journalDetail->debit = round(floatval($AR->deposit_amount));
        $journalDetail->kredit = 0;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();

        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description =  $AR->description ." ( $AR->adr_no - $AR->customer_code )";
        $journalDetail->coa_code = $AR->coa_kredit ;
        $journalDetail->debit =  0 ;
        $journalDetail->kredit = round(floatval($AR->deposit_amount));
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();


    }

    public function journalInvoices($code){

        $invoice = Invoice::join("customers", "customers.code", "=", "invoices.customer_code")
                    ->join("coa", "coa.code", "=", "customers.coa_code")
                    ->select("invoices.*","invoices.transaction_date", "customers.code as customer_code", "customers.name as customer_name", "coa.code as coa_code", "coa.name as coa_name")
                    ->where("invoice_no", $code)
                    ->first();

        $supplyModel = Journal::where("voucher_no", 'like', "%JPEN%")->orderBy("voucher_no", "desc")->lockForUpdate()->first();
        $AutomaticCode = $this->automaticCode("JPEN", $supplyModel,true,"voucher_no");


        // Insert Header Journal
        $journal = New Journal();
        $journal->voucher_no = $AutomaticCode;
        $journal->transaction_date = $invoice->transaction_date;
        $journal->ref_no = $invoice->invoice_no;
        $journal->journal_type_code = "JPEN";
        $journal->posting_status = 0;
        $journal->created_by =Auth::user()->username;
        $journal->save();


        //Insert Detail Journal
        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description =  $invoice->description ." $invoice->invoice_no - $invoice->customer_code ";
        $journalDetail->coa_code = $invoice->coa_code;
        $journalDetail->debit = round(floatval($invoice->grand_total));
        $journalDetail->kredit = 0;
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();


        if (floatval($invoice->percent_ppn) > 0){
            $journalDetail  = New Journal_Detail();
            $journalDetail->voucher_no = $journal->voucher_no;
            $journalDetail->description =  $invoice->description ."  $invoice->invoice_no - $invoice->customer_code ";
            $journalDetail->coa_code = '20.01.02.02';
            $journalDetail->debit = 0;
            $journalDetail->kredit = round(floatval($invoice->ppn_amount));
            $journalDetail->created_by = Auth::user()->username;
            $journalDetail->save();
        }
        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description =  $invoice->description ."  $invoice->invoice_no - $invoice->customer_code ";
        $journalDetail->coa_code = $invoice->coa_revenue;
        $journalDetail->debit =  0 ;
        $journalDetail->kredit = round(floatval($invoice->total));
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();
    }

    public function journalReceipt($code){
        $CR = Cash_Receive::where('bkm_no', $code)->join("customers", "customers.code", "=", "cash_receives.customer_code")
        ->select("cash_receives.*", "customers.name as customer_name", "customers.coa_code as coa_piutang")->first();

        $supplyModel = Journal::where("voucher_no", 'like', "%JKM%")->orderBy("voucher_no", "desc")->lockForUpdate()->first();
        $AutomaticCode = $this->automaticCode("JKM", $supplyModel,true,"voucher_no");

         // Insert Header Journal
         $journal = New Journal();
         $journal->voucher_no = $AutomaticCode;
         $journal->transaction_date = $CR->transaction_date;
         $journal->ref_no = $CR->bkm_no;
         $journal->journal_type_code = "JKM";
         $journal->posting_status = 0;
         $journal->created_by =Auth::user()->username;
         $journal->save();

         //Insert Detail Journal

        if(floatval($CR->cash_amount) > 0){

            $journalDetail  = New Journal_Detail();
            $journalDetail->voucher_no = $journal->voucher_no;
            $journalDetail->description =  'Kas/Bank Masuk Pembayaran Piutang' ." ( $CR->bkm_no - $CR->ref_no - $CR->customer_code )";
            $journalDetail->coa_code = $CR->coa_cash_code;
            $journalDetail->debit = round(floatval($CR->cash_amount));
            $journalDetail->kredit = 0;
            $journalDetail->created_by = Auth::user()->username;
            $journalDetail->save();

        }

        if(floatval($CR->deposit_amount) > 0){

            $ADR = Advanced_Receipt::where('customer_code',$CR->customer_code)->first();

            $journalDetail  = New Journal_Detail();
            $journalDetail->voucher_no = $journal->voucher_no;
            $journalDetail->description =  'Alokasi Dp Pembayaran' ." ( $CR->bkm_no - $CR->ref_no - $CR->customer_code )";
            $journalDetail->coa_code = $ADR->coa_kredit;
            $journalDetail->debit = round(floatval($CR->deposit_amount));
            $journalDetail->kredit = 0;
            $journalDetail->created_by = Auth::user()->username;
            $journalDetail->save();
        }

        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description =  'Pembayaran Piutang' ." ( $CR->bkm_no - $CR->ref_no - $CR->customer_code )";
        $journalDetail->coa_code = $CR->coa_piutang;
        $journalDetail->debit =  0 ;
        $journalDetail->kredit = round(floatval($CR->total_amount));
        $journalDetail->created_by = Auth::user()->username;
        $journalDetail->save();


    }
}
