<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Project;
use App\Models\Purchase_Request;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
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
}
