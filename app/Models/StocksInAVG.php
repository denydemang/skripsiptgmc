<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StocksInAVG extends Model
{
    use HasFactory;
    protected $table = "stocksin_avg";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $guarded = [];
}
