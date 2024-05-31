<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $primaryKey = "invoice_no";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'invoice_no',
        'transaction_date',
        'customer_code',
        'project_realisation_code',
        'bap_no',
        'bapp_no',
        'spp_no',
        'coa_revenue',
        'total',
        'pph_percent',
        'pph_amount',
        'percent_ppn',
        'ppn_amount',
        'grand_total',
        'paid_amount',
        'terbilang',
        'description',
        'is_approve',
        'approved_by',
        'payment_term_code',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
