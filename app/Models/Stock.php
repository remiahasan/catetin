<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $table = 'stock';
    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function stockLog()
    {
        return $this->hasMany(StockLog::class, 'stock_id');
    }

    public function getHargaFormattedAttribute()
    {
        return number_format($this->harga, 0, ',', '.');
    }
}
