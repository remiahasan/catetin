<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\StockLog;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index($id, Request $request)
    {
        $tanggal = $request->query('date', now()->toDateString());
        // Ambil tanggal awal dan akhir dari query string (jika ada)

        $startDate = $request->query('start', now()->toDateString());
        $endDate = $request->query('end', $startDate); // jika end kosong, gunakan start

        // Ambil data business + transaksi dalam rentang tanggal
        $business = Business::with([
            'transaksis' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [
                    \Carbon\Carbon::parse($startDate)->startOfDay(),
                    \Carbon\Carbon::parse($endDate)->endOfDay(),
                ]);
            },
            'users' // biar bagian pegawai tetap bisa diakses di view
        ])->findOrFail($id);

        // Ambil stock log berdasarkan business dan rentang tanggal
        $stocks = StockLog::with('stocks')
            ->whereHas('stocks', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->whereBetween('created_at', [
                \Carbon\Carbon::parse($startDate)->startOfDay(),
                \Carbon\Carbon::parse($endDate)->endOfDay(),
            ])
            ->orderBy('stok_keluar', 'desc')
            ->get();

        $allItems = [];

        foreach ($business->transaksis as $transaksi) {
            $items = json_decode($transaksi->details, true) ?? [];
            foreach ($items as $item) {
                $allItems[] = [
                    'nama' => $item['nama'] ?? '-',
                    'jumlah' => $item['jumlah'] ?? 0,
                    'harga' => $item['harga'] ?? 0,
                    'total' => ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0),
                ];
            }
        }

        $allItems = collect($allItems)
            ->groupBy('nama')
            ->map(function ($items) {
                $jumlah = $items->sum('jumlah');
                $harga = $items->first()['harga'] ?? 0;

                return [
                    'nama' => $items->first()['nama'],
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'total' => $jumlah * $harga,
                ];
            })
            ->sortByDesc('jumlah')
            ->values();

        // Pagination manual (karena stoknya pakai Collection, bukan QueryBuilder)
        $perPage = 5;
        $currentPage = $request->get('page', 1);
        $total = $stocks->count();
        $stocks = $stocks->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // Kirim data ke view
        return view('admin.laporan.detailLaporan', compact(
            'business',
            'startDate',
            'endDate',
            'stocks',
            'total',
            'currentPage',
            'perPage',
            'allItems',
            'tanggal'
        ));
    }

    public function getData(Request $request)
    {
        dd($request->query('date'));
        // Ambil query string ?date=YYYY-MM-DD
        $date = $request->query('date', now()->toDateString());

        $businesses = Business::withCount([
            'stocks' => function ($q) use ($date) {
                $q->whereDate('created_at', $date);
            }
        ])
            ->with([
                'transaksis' => function ($q) use ($date) {
                    $q->whereDate('created_at', $date);
                }
            ])
            ->get();

        $data = $businesses->map(function ($b) {
            return [
                'id' => $b->id,
                'name' => $b->name,
                'pendapatan' => $b->transaksis->sum('total_bayar'),
                'transaksi_count' => $b->transaksis->count(),
                'stocks_count' => $b->stocks_count, // dari withCount
            ];
        });

        return response()->json($data);
    }

    public function getStocks($id)
    {
        $usaha = Business::with('stocks')->findOrFail($id);
        return response()->json($usaha->stocks);
    }

    public function detailLaporan($id, Request $request)
    {
        $tanggal = $request->query('date', now()->toDateString());

        // Ambil data business + transaksinya di tanggal tersebut
        $business = Business::with([
            'transaksis' => function ($q) use ($tanggal) {
                $q->whereDate('created_at', $tanggal);
            }
        ])->findOrFail($id);

        // Ambil stock log berdasarkan business
        $stocks = StockLog::with('stocks')
            ->whereHas('stocks', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })->whereDate('created_at', $tanggal)
            ->get();

        // Pagination manual
        $perPage = 5;
        $currentPage = $request->get('page', 1);
        $total = $stocks->count();
        $stocks = $stocks->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return view('admin.laporan.detailLaporan', compact('business', 'tanggal', 'stocks', 'total', 'currentPage', 'perPage'));
    }

    public function laporanPegawai($id, Request $request)
    {
        $tanggal = $request->query('date', now()->toDateString());

        $pegawai = \App\Models\User::findOrFail($id);

        $transaksis = \App\Models\Transaksi::with(['business', 'user'])
            ->where('user_id', $id)
            ->whereDate('created_at', $tanggal)
            ->get();

        $jumlahTransaksi = $transaksis->count();

        return view('admin.laporan.laporanPegawai', compact('pegawai', 'transaksis', 'jumlahTransaksi', 'tanggal'));
    }
}
