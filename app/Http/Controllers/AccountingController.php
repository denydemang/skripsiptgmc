<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Journal_Detail;
use App\Models\Project;
use App\Models\Project_Detail;
use App\Models\Project_Detail_B_Realisation;
use App\Models\Project_Detail_Realisations;
use App\Models\ProjectDetailB;
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

        // Insert Beban Material Jurnal Detail
        $cogsMaterial = Stocks_Out::where("ref_no", $ref_no)->selectRaw('SUM(qty * cogs) as total')->first();
        $cogsMaterial = floatval($cogsMaterial->total);

        $journalDetail  = New Journal_Detail();
        $journalDetail->voucher_no = $journal->voucher_no;
        $journalDetail->description = "Beban Material Untuk Pengerjaan Project Code : ". $project->code ;
        $journalDetail->coa_code = $project->coa_expense;
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

        // Insert  Beban TKL Journal Detail
        $totalutanggajitkl = 0;
        foreach ($Variation_COA_ProjectDetailB as $coa){
            $journalDetail  = New Journal_Detail();
            $totalutanggajitkl+= floatval($coa->totalcogs);
            $journalDetail->voucher_no = $journal->voucher_no;
            $journalDetail->description = "Beban Tenaga Kerja Langung Untuk Pengerjaan Project Code: $project->code"  ;
            $journalDetail->coa_code = $coa->coa_code;
            $journalDetail->debit = floatval($coa->totalcogs);
            $journalDetail->kredit = 0;
            $journalDetail->created_by = Auth::user()->username;
            $journalDetail->save();
        }

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
}
