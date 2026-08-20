<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockLog extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $table = 'stock_log';
    protected $guarded = [];

    public function stocks()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
}
