<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class RiwayatTransaksiController extends Controller
{
    public function index()
    {
        // Ambil transaksi berdasarkan user (pegawai) yang login
        $transaksis = Transaksi::where('user_id', Auth::id())
            ->whereDate('created_at', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->get();

        $totalHariIni = $transaksis->sum('total_bayar');

        // Kirim data ke view
        return view('pegawai.riwayat-transaksi.index', compact('transaksis','totalHariIni'));
    }
}
