<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StocksOutAVG extends Model
{
    use HasFactory;
    protected $table = "stocksout_avg";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $guarded = [];
}
