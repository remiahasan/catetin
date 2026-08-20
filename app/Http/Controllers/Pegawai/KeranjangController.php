<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Pengguna tidak terautentikasi.'], 401);
            }

            $businessId = $user->id_business;

            $request->validate([
                'menu_id' => 'required|exists:menus,id',
                'jumlah' => 'required|integer|min:1',
                'harga_satuan' => 'required|numeric|min:0',
                'ukuran' => 'nullable|string|max:255',
                'total_harga' => 'required|numeric|min:0',
            ]);

            $keranjangItem = Keranjang::where('menu_id', $request->menu_id)
                ->where('business_id', $businessId)
                // ->where('ukuran', $request->ukuran)
                ->first();

            if ($keranjangItem) {
                $keranjangItem->jumlah += $request->jumlah;
                $keranjangItem->total_harga = $keranjangItem->jumlah * $keranjangItem->harga_satuan;
                $keranjangItem->save();
            } else {
                $keranjang = new Keranjang();
                $keranjang->menu_id = $request->menu_id;
                $keranjang->business_id = $businessId;
                $keranjang->jumlah = $request->jumlah;
                $keranjang->harga_satuan = $request->harga_satuan;
                $keranjang->total_harga = $request->jumlah * $request->harga_satuan;
                // $keranjang->ukuran = $request->ukuran;
                $keranjang->save();
                $keranjangItem = $keranjang;
            }

            $totalBayarSekarang = Keranjang::where('business_id', $businessId)->sum('total_harga');

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan ke keranjang.',
                'data' => $keranjangItem,
                'total_bayar' => $totalBayarSekarang,
                'total_bayar_formatted' => number_format($totalBayarSekarang, 0, ',', '.'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function viewCart()
    {
        $businessId = Auth::user()->id_business;

        $keranjangs = Keranjang::with('menu')->where('business_id', $businessId)->get();
        $totalBayar = $keranjangs->sum('total_harga');

        return view('pegawai.keranjang.index', compact('keranjangs', 'totalBayar'));
    }

    public function updateQuantity(Request $request, $id)
    {
        $keranjang = Keranjang::with('menu')->findOrFail($id);

        // Update berdasarkan aksi (increment/decrement)
        if ($request->has('action')) {
            if ($request->action == 'increment') {
                $keranjang->jumlah += 1;
            } elseif ($request->action == 'decrement') {
                $keranjang->jumlah = max(1, $keranjang->jumlah - 1);
            }
        } else {
            // Jika tidak ada action, fallback ke validasi jumlah
            $request->validate([
                'jumlah' => 'required|integer|min:1',
            ]);
            $keranjang->jumlah = $request->jumlah;
        }

        // Harga berdasarkan ukuran
        $harga = $keranjang->menu->harga; // default fallback

        $keranjang->harga_satuan = $harga;
        $keranjang->total_harga = $keranjang->jumlah * $harga;
        $keranjang->save();

        $totalBayarSekarang = Keranjang::where('business_id', Auth::user()->id_business)->sum('total_harga');

        return response()->json([
            'success' => true,
            'jumlah_baru' => $keranjang->jumlah,
            'total_harga_formatted' => number_format($keranjang->total_harga, 0, ',', '.'),
            'total_bayar_formatted' => number_format($totalBayarSekarang, 0, ',', '.'),
        ]);
    }

    public function removeFromCart($id)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Pengguna tidak terautentikasi.');
        }

        $keranjang = Keranjang::where('id', $id)
            ->where('business_id', $user->id_business)
            ->firstOrFail();

        $keranjang->delete();

        $totalBayarSekarang = Keranjang::where('business_id', $user->id_business)->sum('total_harga');

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang.',
            'total_bayar' => $totalBayarSekarang,
            'total_bayar_formatted' => number_format($totalBayarSekarang, 0, ',', '.'),
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'uang_dibayarkan' => 'required|numeric|min:0',
        ]);

        $businessId = Auth::user()->id_business;
        $keranjangs = Keranjang::where('business_id', $businessId)->with('menu')->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong, tidak ada yang bisa dibayar.');
        }

        $totalBayar = $keranjangs->sum('total_harga');

        if ($request->uang_dibayarkan < $totalBayar) {
            return redirect()->back()->with('error', 'Uang yang dibayarkan tidak cukup.');
        }

        $kembalian = $request->uang_dibayarkan - $totalBayar;

        $details = $keranjangs->map(function ($keranjang) {
            return [
                'menu_id' => $keranjang->menu_id,
                'nama' => $keranjang->menu->nama ?? '-',
                'jumlah' => $keranjang->jumlah,
                'harga' => $keranjang->harga_satuan,
                'subtotal' => $keranjang->total_harga,
                'extra_topping' => $keranjang->extra_topping ?? null,
            ];
        });

        Transaksi::create([
            'user_id' => Auth::id(),
            'business_id' => $businessId,
            'total_bayar' => $totalBayar,
            'uang_dibayarkan' => $request->uang_dibayarkan,
            'kembalian' => $kembalian,
            'details' => $details->toJson(),
        ]);

        Keranjang::where('business_id', $businessId)->delete();

        return redirect()
            ->route('pegawai.transaksi.index');
    }
}
