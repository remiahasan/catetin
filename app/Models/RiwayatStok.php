<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    /** @use HasFactory<\Database\Factories\RiwayatStokFactory> */
    use HasFactory;
    protected $table = 'riwayat_stoks';
    protected $guarded = [];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
