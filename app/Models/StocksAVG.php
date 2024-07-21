<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StocksAVG extends Model
{
    use HasFactory;
    protected $table = "stocksavg";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $guarded = [];
}
