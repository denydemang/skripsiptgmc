<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PrintController extends Controller
{
    public function printJournalProject($id){

        $journal = Project::join("journals",  "projects.code", "=", "journals.ref_no")
        ->join("journal_details", "journals.voucher_no", "=", "journal_details.voucher_no")
        ->join("coa", "journal_details.coa_code", "=", "coa.code")
        ->select("projects.name as project_name", "coa.name as coa_name", "journals.*", "journal_details.*")
        ->where("projects.code", $id)
        ->get();

        $totalDebit = 0;
        $totalKredit = 0;

        foreach($journal as $j){
            $totalDebit +=  floatval($j->debit);
            $totalKredit += floatval($j->kredit);
        }

        $data =[
            "dataprojectdanjurnal" => $journal,
            "totalDebit" => $totalDebit,
            "totalKredit" => $totalKredit
        ];
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'landscape'); 
        $pdf->loadview("admin.project.print.printjournalproject", $data);
        return $pdf->stream("JournalPorject-$id.pdf", array("Attachment" => false));
    }
}
