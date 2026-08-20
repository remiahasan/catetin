<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Transaksi;
use App\Models\Stock;
use App\Models\StockLog;
use App\Models\StokLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $filterPendapatan = $request->get('filter_pendapatan', 'minggu');
        $filterStok = $request->get('filter_stok', 'minggu');
        $filterMenu = $request->get('filter_menu', 'minggu');

        if ($filterPendapatan == 'bulan') {
            // =================== PENJUALAN (TRANSAKSI) ===================
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();

            $weeks = [];
            $categoriesPendapatan = [];
            $current = $startOfMonth->copy();
            while ($current <= $endOfMonth) {
                $startOfWeek = $current->copy();
                $endOfWeek = $current->copy()->endOfWeek();
                if ($endOfWeek > $endOfMonth)
                    $endOfWeek = $endOfMonth->copy();

                $weeks[] = [
                    'start' => $startOfWeek->copy(),
                    'end' => $endOfWeek->copy(),
                    'label' => $startOfWeek->format('j') . ' - ' . $endOfWeek->format('j M'),
                    'yearweek' => $startOfWeek->format('oW'),
                ];

                $categoriesPendapatan[] = $startOfWeek->format('j') . ' - ' . $endOfWeek->format('j M');
                $current = $endOfWeek->addDay();
            }

            $transactions = DB::table('transaksis')
                ->join('business', 'transaksis.business_id', '=', 'business.id')
                ->selectRaw('YEARWEEK(transaksis.created_at, 3) as yearweek, business.name as business, SUM(transaksis.total_bayar) as total_pendapatan')
                ->whereBetween('transaksis.created_at', [$startOfMonth, $endOfMonth])
                ->groupByRaw('YEARWEEK(transaksis.created_at, 3), business.name')
                ->orderBy('yearweek', 'asc')
                ->get();

            $seriesPendapatan = [];
            foreach ($transactions->groupBy('business') as $usaha => $data) {
                $seriesPendapatan[] = [
                    'name' => $usaha,
                    'data' => collect($weeks)->map(function ($week) use ($data) {
                        $row = $data->firstWhere('yearweek', $week['yearweek']);
                        return [
                            'pendapatan' => $row ? $row->total_pendapatan : 0,
                            // 'transaksi'  => $row ? $row->total_transaksi : 0,
                        ];
                    })->values()->toArray()
                ];
            }
            $totalPendapatan = $transactions->sum('total_pendapatan');
        } else {
            // =================== PENJUALAN (TRANSAKSI) ===================
            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();

            $transactions = DB::table('transaksis')
                ->join('business', 'transaksis.business_id', '=', 'business.id')
                ->selectRaw('DATE(transaksis.created_at) as tanggal, business.name as business, SUM(transaksis.total_bayar) as total_pendapatan')
                ->whereBetween('transaksis.created_at', [$startOfWeek, $endOfWeek])
                ->groupByRaw('DATE(transaksis.created_at), business.name')
                ->orderBy('tanggal', 'asc')
                ->get();

            $categoriesPendapatan = collect();
            for ($date = $startOfWeek->copy(); $date <= $endOfWeek; $date->addDay()) {
                $categoriesPendapatan->push($date->format('Y-m-d'));
            }

            $seriesPendapatan = [];
            foreach ($transactions->groupBy('business') as $usaha => $data) {
                $seriesPendapatan[] = [
                    'name' => $usaha,
                    'data' => $categoriesPendapatan->map(function ($tanggal) use ($data) {
                        $row = $data->firstWhere('tanggal', $tanggal);
                        return [
                            'pendapatan' => $row ? $row->total_pendapatan : 0,
                            // 'transaksi'  => $row ? $row->total_transaksi : 0,
                        ];
                    })->values()->toArray()
                ];
            }
            $totalPendapatan = $transactions->sum('total_pendapatan');
        }


        if ($filterStok == 'bulan') {
            // =================== STOK KELUAR ===================

            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();

            $weeks = [];
            $categoriesStok = [];
            $current = $startOfMonth->copy();
            while ($current <= $endOfMonth) {
                $startOfWeek = $current->copy();
                $endOfWeek = $current->copy()->endOfWeek();
                if ($endOfWeek > $endOfMonth)
                    $endOfWeek = $endOfMonth->copy();

                $weeks[] = [
                    'start' => $startOfWeek->copy(),
                    'end' => $endOfWeek->copy(),
                    'label' => $startOfWeek->format('j') . ' - ' . $endOfWeek->format('j M'),
                    'yearweek' => $startOfWeek->format('oW'),
                ];

                $categoriesStok[] = $startOfWeek->format('j') . ' - ' . $endOfWeek->format('j M');
                $current = $endOfWeek->addDay();
            }
            $stokLogs = DB::table('stock_log')
                ->join('stock', 'stock_log.stock_id', '=', 'stock.id')
                ->join('business', 'stock.business_id', '=', 'business.id')
                ->selectRaw('YEARWEEK(stock_log.created_at, 3) as yearweek, business.name as business, SUM(stock_log.stok_keluar) as total_stok_keluar')
                ->whereBetween('stock_log.created_at', [$startOfMonth, $endOfMonth])
                ->groupByRaw('YEARWEEK(stock_log.created_at, 3), business.name')
                ->orderBy('yearweek', 'asc')
                ->get();

            $stokSeries = [];
            foreach ($stokLogs->groupBy('business') as $usaha => $data) {
                $stokSeries[] = [
                    'name' => $usaha,
                    'data' => collect($weeks)->map(function ($week) use ($data) {
                        $row = $data->firstWhere('yearweek', $week['yearweek']);
                        return [
                            'stok_keluar' => $row ? $row->total_stok_keluar : 0,
                        ];
                    })->values()->toArray()
                ];
            }

            $totalStokKeluar = $stokLogs->sum('total_stok_keluar');
        } else {
            // =================== STOK KELUAR ===================

            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();

            $stokLogs = DB::table('stock_log')
                ->join('stock', 'stock_log.stock_id', '=', 'stock.id')
                ->join('business', 'stock.business_id', '=', 'business.id')
                ->selectRaw('DATE(stock_log.created_at) as tanggal, business.name as business, SUM(stock_log.stok_keluar) as total_keluar')
                ->groupByRaw('DATE(stock_log.created_at), business.name')
                ->orderBy('tanggal', 'asc')
                ->get();
            $categoriesStok = collect();
            for ($date = $startOfWeek->copy(); $date <= $endOfWeek; $date->addDay()) {
                $categoriesStok->push($date->format('Y-m-d'));
            }
            $stokSeries = [];
            foreach ($stokLogs->groupBy('business') as $usaha => $data) {
                $stokSeries[] = [
                    'name' => $usaha,
                    'data' => $categoriesStok->map(function ($tanggal) use ($data) {
                        $row = $data->firstWhere('tanggal', $tanggal);
                        return [
                            'stok_keluar' => $row ? $row->total_keluar : 0,
                        ];
                    })
                ];
            }

            $totalStokKeluar = $stokLogs->sum('total_keluar');
        }


        if ($filterMenu == 'bulan') {
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();

            $weeks = [];
            $categoriesMenu = [];
            $current = $startOfMonth->copy();
            while ($current <= $endOfMonth) {
                $startOfWeek = $current->copy();
                $endOfWeek = $current->copy()->endOfWeek();
                if ($endOfWeek > $endOfMonth)
                    $endOfWeek = $endOfMonth->copy();

                $weeks[] = [
                    'start' => $startOfWeek->copy(),
                    'end' => $endOfWeek->copy(),
                    'label' => $startOfWeek->format('j') . ' - ' . $endOfWeek->format('j M'),
                    'yearweek' => $startOfWeek->format('oW'),
                ];

                $categoriesMenu[] = $startOfWeek->format('j') . ' - ' . $endOfWeek->format('j M');
                $current = $endOfWeek->addDay();
            }

            // ambil transaksi di bulan ini (dengan business name & details)
            $transaksis = DB::table('transaksis')
                ->join('business', 'transaksis.business_id', '=', 'business.id')
                ->select('transaksis.id', 'business.name as business', 'transaksis.details', 'transaksis.created_at')
                ->whereBetween('transaksis.created_at', [$startOfMonth, $endOfMonth])
                ->orderBy('transaksis.created_at', 'asc')
                ->get();

            // gunakan array untuk mengakumulasi per (yearweek,business)
            $menuTerjualArr = [];

            foreach ($transaksis as $trx) {
                $yearweek = Carbon::parse($trx->created_at)->format('oW');
                $details = json_decode($trx->details, true) ?: [];

                // pastikan mengambil properti 'jumlah' jika ada
                $totalJumlah = collect($details)->sum(function ($it) {
                    return isset($it['jumlah']) ? (int) $it['jumlah'] : 0;
                });

                $key = $yearweek . '|' . $trx->business;

                if (!isset($menuTerjualArr[$key])) {
                    $menuTerjualArr[$key] = [
                        'yearweek' => $yearweek,
                        'business' => $trx->business,
                        'total_menu_terjual' => 0,
                    ];
                }

                $menuTerjualArr[$key]['total_menu_terjual'] += $totalJumlah;
            }

            // collect untuk mempermudah groupBy
            $menuTerjual = collect(array_values($menuTerjualArr));

            // bangun series per business, urut sesuai $weeks (so data index cocok dengan categories)
            $menuSeries = [];
            foreach ($menuTerjual->groupBy('business') as $business => $group) {
                $seriesData = collect($weeks)->map(function ($week) use ($group) {
                    $row = $group->firstWhere('yearweek', $week['yearweek']);
                    return $row ? (int) $row['total_menu_terjual'] : 0;
                })->values()->toArray();

                $menuSeries[] = [
                    'name' => $business,
                    'data' => collect($seriesData)->map(fn($jumlah) => ['jumlah_menu' => $jumlah])->toArray(),
                ];
            }

            $totalMenuTerjual = $menuTerjual->sum('total_menu_terjual');
        } else {
            // versi minggu (per-hari)
            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();

            $transaksis = DB::table('transaksis')
                ->join('business', 'transaksis.business_id', '=', 'business.id')
                ->select('transaksis.id', 'business.name as business', 'transaksis.details', 'transaksis.created_at')
                ->whereBetween('transaksis.created_at', [$startOfWeek, $endOfWeek])
                ->orderBy('transaksis.created_at', 'asc')
                ->get();

            $categoriesMenu = collect();
            for ($date = $startOfWeek->copy(); $date <= $endOfWeek; $date->addDay()) {
                $categoriesMenu->push($date->format('Y-m-d'));
            }

            $menuTerjualArr = [];

            foreach ($transaksis as $trx) {
                $tanggal = Carbon::parse($trx->created_at)->format('Y-m-d');
                $details = json_decode($trx->details, true) ?: [];

                $totalJumlah = collect($details)->sum(function ($it) {
                    return isset($it['jumlah']) ? (int) $it['jumlah'] : 0;
                });

                $key = $tanggal . '|' . $trx->business;

                if (!isset($menuTerjualArr[$key])) {
                    $menuTerjualArr[$key] = [
                        'tanggal' => $tanggal,
                        'business' => $trx->business,
                        'total_menu_terjual' => 0,
                    ];
                }

                $menuTerjualArr[$key]['total_menu_terjual'] += $totalJumlah;
            }

            $menuTerjual = collect(array_values($menuTerjualArr));

            $menuSeries = [];
            foreach ($menuTerjual->groupBy('business') as $business => $group) {
                $seriesData = $categoriesMenu->map(function ($tanggal) use ($group) {
                    $row = $group->firstWhere('tanggal', $tanggal);
                    return $row ? (int) $row['total_menu_terjual'] : 0;
                })->values()->toArray();

                $menuSeries[] = [
                    'name' => $business,
                    'data' => collect($seriesData)->map(fn($jumlah) => ['jumlah_menu' => $jumlah])->toArray(),
                ];
            }

            // =================== MENU BEST SELLER ===================
            $filterBestSeller = $request->get('filter_best_seller', 'hari');

            // Tentukan rentang waktu
            if ($filterBestSeller == 'bulan') {
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
            } elseif ($filterBestSeller == 'minggu') {
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
            } else {
                $start = now()->startOfDay();
                $end = now()->endOfDay();
            }

            $bestSellerRaw = Transaksi::whereBetween('created_at', [$start, $end])->get();

            $menuCount = [];

            foreach ($bestSellerRaw as $trx) {
                $details = json_decode($trx->details, true);

                if (is_array($details)) {
                    foreach ($details as $item) {

                        // agar tidak tabrakan antar business
                        $key = ($trx->business_id ?? 'X') . '|' . ($item['nama'] ?? '-');

                        $jumlah = $item['jumlah'] ?? 1;

                        if (!isset($menuCount[$key])) {
                            $menuCount[$key] = 0;
                        }

                        $menuCount[$key] += $jumlah;
                    }
                }
            }

            // Convert ke bentuk tabel
            $bestSeller = collect($menuCount)
                ->map(function ($jumlah, $key) {
                    [$business_id, $nama] = explode('|', $key);
                    return [
                        'nama' => $nama,
                        'jumlah' => $jumlah,
                        'business_id' => intval($business_id),
                        'business_name' => Business::find($business_id)->name ?? '-',
                    ];
                })
                ->sortByDesc('jumlah')
                ->take(10)
                ->values();
            $totalMenuTerjual = $menuTerjual->sum('total_menu_terjual');

            // =================== STOK SERING KELUAR ===================
            $filterStokKeluar = request()->get('filter_stok_keluar', 'hari');

            // FILTER RANGE
            if ($filterStokKeluar == 'hari') {
                $range = [now()->startOfDay(), now()->endOfDay()];
            } elseif ($filterStokKeluar == 'minggu') {
                $range = [now()->startOfWeek(), now()->endOfWeek()];
            } else { // bulan
                $range = [now()->startOfMonth(), now()->endOfMonth()];
            }

            $stokSeringKeluar = DB::table('riwayat_stoks')
                ->join('stock', 'riwayat_stoks.stock_id', '=', 'stock.id')
                ->join('business', 'stock.business_id', '=', 'business.id')
                ->select(
                    'stock.nama as nama_stock',
                    'business.name as business_name',
                    DB::raw('SUM(riwayat_stoks.jumlah) as total_keluar')
                )

                ->where('riwayat_stoks.status', 'keluar')
                ->whereBetween('riwayat_stoks.created_at', $range)
                ->groupBy('riwayat_stoks.stock_id', 'business.name')
                ->orderByDesc('total_keluar')
                ->take(10)
                ->get();
        }

        return view('admin.dashboard', [
            'filterMenu' => $filterMenu,
            'categoriesMenu' => $categoriesMenu,
            'menuSeries' => $menuSeries,
            'totalMenuTerjual' => $totalMenuTerjual,
            'categoriesPendapatan' => $categoriesPendapatan,
            'categoriesStok' => $categoriesStok,
            'seriesPendapatan' => $seriesPendapatan,          // pendapatan + transaksi
            'stokSeries' => $stokSeries,  // stok keluar
            'filterPendapatan' => $filterPendapatan,
            'filterStok' => $filterStok,
            'totalPendapatan' => $totalPendapatan,
            'totalStokKeluar' => $totalStokKeluar,
            'bestSeller' => $bestSeller ?? collect(),
            'filterBestSeller' => $filterBestSeller,
            'stokSeringKeluar' => $stokSeringKeluar,
            'filterStokKeluar' => $filterStokKeluar,

        ]);
    }
}
