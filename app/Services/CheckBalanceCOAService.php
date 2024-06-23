<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CheckBalanceCOAService
{

    public string $coaName = "";
    public string $coaCode = "";
    public float $balance;

    public function isValidAmount(float $paidAmountInput, string $coaCode, float $decreaseAmount = 0) :  bool
    {

        $currentDate = Carbon::now()->format('Y-m-d');
        $result = DB::select("
                SELECT 
                coalist.coa_code,
                coalist.coa_name,
                coalist.beginning_balance + IFNULL( saldo.BalanceAmount, 0 ) as BalanceAmount
                FROM

                (
                SELECT
                        coa.code as coa_code,
                        coa.name as coa_name,
                        coa.default_dk,
                        ifnull(coa.beginning_balance, 0) as beginning_balance
                from coa
                where coa.code = ? AND
                coa.description <> 'Header'

                ) as coalist
                LEFT JOIN
                (SELECT
                balance.coa_code,
                balance.coa_name,
                balance.BalanceAmount
                from
                (SELECT
                qry.coa_code,
                qry.coa_name,
                CASE
                    default_dk 
                    WHEN 'D' THEN
                    IFNULL( SUM( qry.debit - qry.kredit ), 0 ) ELSE IFNULL( SUM( qry.kredit - qry.debit ), 0 ) 
                END AS BalanceAmount 
                FROM
                (
                SELECT
                    j.voucher_no,
                    j.transaction_date,
                    j.ref_no,
                    j.journal_type_code,
                    jd.coa_code,
                    coa.`name` AS coa_name,
                    jd.description,
                    jd.debit,
                    jd.kredit,
                    coa.beginning_balance,
                    coa.default_dk 
                FROM
                    journals j
                    INNER JOIN journal_details jd ON j.voucher_no = jd.voucher_no
                    INNER JOIN coa ON coa.`code` = jd.coa_code 
                
                ) qry 
                WHERE
                CAST( qry.transaction_date AS DATE ) <= ?
                GROUP BY
                qry.coa_code,
                qry.coa_name
                ) balance
                ) as saldo

                ON saldo.coa_code = coalist.coa_code


        
        ", [$coaCode, $currentDate]);


        $this->coaCode = $result[0]->coa_code;
        $this->coaName =$result[0]->coa_name;
        $this->balance = $result[0]->BalanceAmount;


        return $paidAmountInput <= $this->balance - $decreaseAmount; 
    }
    
}
