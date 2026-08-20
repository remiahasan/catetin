<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // protected $fillable = ['total_bayar', 'user_id'];
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id'); // Relasi ke tabel businesses
    }
}
