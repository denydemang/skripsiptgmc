<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Journal_Detail;
use App\Models\Project_Detail;
use App\Models\ProjectDetailB;
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
}
