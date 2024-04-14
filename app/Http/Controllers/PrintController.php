<?php

namespace App\Http\Controllers;

use App\Models\Journal;
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
        ->where("journals.journal_type_code", "!=", "jp")
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

        $totalDebitPenyesuaian = 0;
        $totalKreditPenyesuian = 0;
        foreach($journalPenyesuaian as $j){
            $totalDebitPenyesuaian +=  floatval($j->debit);
            $totalKreditPenyesuian += floatval($j->kredit);
        }

        $data =[
            "dataprojectdanjurnal" => $journal,
            "jurnalpenyesuaian" => $journalPenyesuaian,
            "totalDebit" => $totalDebit,
            "totalKredit" => $totalKredit,
            "totalDebitPenyesuaian" => $totalDebitPenyesuaian,
            "totalKreditPenyesuian" => $totalKreditPenyesuian,
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

    }
}
