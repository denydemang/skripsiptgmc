<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    use HasFactory;
    protected $table = "payment_terms";
    protected $primaryKey = "code";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'code',
        'term_name',
        'description',
        'due_days',
        'discount_days',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
