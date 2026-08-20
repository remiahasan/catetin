<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    // protected $fillable = ['menu_id', 'jumlah', 'total_harga'];
    protected $guarded = [];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
