<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\RiwayatStok;
use App\Models\Stock;
use App\Models\StockLog;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageStockController extends Controller
{


    public function pegawaiView(Request $request)
    {
        $user = Auth::user();
        $business = Business::find($user->id_business);
        $transaksi = Transaksi::where('business_id', $business->id)
            ->where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->get();

        $stocks = Stock::where('business_id', $business->id)->get();

        // $stocks = StockLog::with('stocks')
        //     ->whereHas('stocks', function ($query) use ($business) {
        //         $query->where('business_id', $business->id);
        //     })->latest()
        //     ->get();

        $stocks_akhir = $stocks->filter(function ($log) {
            return !is_null($log->stok_akhir);
        })->values();

        $alreadyUpdated = $stocks_akhir->isNotEmpty();
        // $noStokToday = $stocks->isEmpty();

        return view('pegawai.UpdateStok', compact('user', 'stocks', 'business', 'transaksi', 'alreadyUpdated'));
    }



    public function index($id, Request $request)
    {
        $business_id = $request->get('business_id', $id);
        $stocks = Stock::when($business_id, function ($query, $business_id) {
            return $query->where('business_id', $business_id);
        })->get();

        $businesses = Business::all();

        $datanama = "data master";

        $business_name = Business::find($business_id);

        $riwayatStok = RiwayatStok::latest()->take(5)->get();

        return view('admin.manage-stock.index', compact('stocks', 'business_name', 'riwayatStok', 'businesses', 'datanama', 'business_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah_stok' => 'nullable|integer',
            'harga' => 'nullable|integer',
            'business_id' => 'required|exists:business,id',
            'satuan' => 'required|string|max:50',
        ]);


        $validated['jumlah_stok'] = $validated['jumlah_stok'] ?? 0;

        Stock::create($validated);

        return redirect()->back()->with('success', 'Data stok berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string',
            'satuan' => 'required|string',
        ]);

        $stock = Stock::findOrFail($id);

        $stock->update([
            'nama' => $request->nama,
            'satuan' => $request->satuan,
        ]);

        return redirect()->back()->with('success', 'Data stok berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();
        return redirect()->back()->with('success', 'Data stok berhasil dihapus');
    }

    public function addJumlahStok(Request $request)
    {
        $business_id = $request->query('business_id');

        if (!$business_id) {
            return redirect()->back()->with('error', 'Business ID tidak ditemukan.');
        }

        $stocks = Stock::where('business_id', $business_id)->get();

        return view('admin.manage-stock.add-jumlah-stok', compact('stocks', 'business_id'));
    }

    public function increaseStock(Request $request)
    {
        // Validasi input

        // dd($request->all());
        $validated = $request->validate([
            'stock' => 'required|array',
            'stock.*.id' => 'required|integer|exists:stock,id',
            'stock.*.jumlah_tambah' => 'nullable|integer|min:0',
        ]);

        // Loop tiap stok yang dikirim
        foreach ($validated['stock'] as $data) {
            // Lewati jika tidak menambah apa-apa
            if (empty($data['jumlah_tambah']) || $data['jumlah_tambah'] <= 0) {
                continue;
            }

            $stock = Stock::findOrFail($data['id']);
            $stok_awal = $stock->jumlah_stok;

            // Tambahkan stok
            $stock->jumlah_stok += $data['jumlah_tambah'];
            $stock->save();

            RiwayatStok::create([
                'stock_id' => $stock->id,
                'user_id' => Auth::id(),
                'status' => 'masuk',
                'jumlah' => $data['jumlah_tambah'],
            ]);

            // Simpan log riwayat stok
            // StockLog::create([
            //     'stock_id'   => $stock->id,
            //     'stok_awal'  => $stok_awal,
            //     'stok_tambah' => $data['jumlah_tambah'],
            //     'stok_akhir' => $stock->jumlah_stok,
            // ]);
        }

        return redirect()->back()->with('success', 'Stok berhasil diperbarui.');
    }

    public function reduceStock(Request $request)
    {
        $validated = $request->validate([
            'jumlah_stok' => 'required|array',
            'jumlah_stok.*' => 'nullable|integer|min:0',
        ]);

        $businessId = $request->input('business_id');

        foreach ($validated['jumlah_stok'] as $stockId => $remainingStock) {
            if (is_null($remainingStock))
                continue;

            $stock = Stock::where('id', $stockId)
                ->where('business_id', $businessId)
                ->first();

            if (!$stock)
                continue;

            // $lastLog = StockLog::where('stock_id', $stock->id)
            //     ->latest('created_at')
            //     ->first();

            // $stokAwal = $lastLog ? $lastLog->stok_awal : $stock->jumlah_stok;
            $stokAwal = $stock->jumlah_stok;
            $stokKeluar = $stokAwal - $remainingStock;

            // Update stok utama
            $stock->jumlah_stok = $remainingStock;
            $stock->save();

            // Catat log semua stok seperti biasa
            StockLog::create([
                'stock_id' => $stock->id,
                'stok_awal' => $stokAwal,
                'stok_keluar' => $stokKeluar,
                'stok_akhir' => $remainingStock,
            ]);

            // Tambahkan ke riwayat hanya jika stok berubah
            if ($stokKeluar > 0) {
                RiwayatStok::create([
                    'stock_id' => $stock->id,
                    'user_id'  => Auth::id(),
                    'status'   => 'keluar',
                    'jumlah'   => $stokKeluar,
                ]);
            }
        }

        return redirect()->route('pegawai.transaksi.index')
            ->with('success', 'Sisa stok berhasil diperbarui dan log berhasil disimpan.');
    }

    public function stockHistory(Request $request)
    {
        $business_id = $request->get('business_id');

        // Cek data business
        $business = $business_id
            ? Business::find($business_id)
            : Business::first(); // fallback agar tidak null

        if (!$business) {
            return back()->with('error', 'Data bisnis tidak ditemukan.');
        }

        // Ambil semua data riwayat stok dengan relasi ke stok dan user
        $riwayatStok = RiwayatStok::with(['stock.business', 'user'])
            ->when($business_id, function ($query, $business_id) {
                $query->whereHas('stock', function ($q) use ($business_id) {
                    $q->where('business_id', $business_id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'created_at' => $item->created_at->format('Y-m-d H:i'),
                    'nama' => $item->stock->nama ?? '-',
                    'business_name' => $item->stock->business->name ?? '-',
                    'user_name' => $item->user->name ?? '-',
                    'status' => ucfirst($item->status),
                    'jumlah' => $item->jumlah,
                ];
            });

        $perPage = 15;

        return view('admin.manage-stock.stok-history', compact(
            'riwayatStok',
            'business_id',
            'business',
            'perPage'
        ));
    }
}
