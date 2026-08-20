<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiTransaksiController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $categories = Category::where('business_id', $user->id_business)->get();

        return view('pegawai.transaksi.index', compact('categories'));
    }

    private function getBestSellerMenuId($businessId)
    {
        $transaksi = \DB::table('transaksis')
            ->where('business_id', $businessId)
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->get();

        $counter = [];

        foreach ($transaksi as $trx) {
            $details = json_decode($trx->details, true);
            if (!is_array($details))
                continue;

            foreach ($details as $item) {
                if (!isset($counter[$item['menu_id']])) {
                    $counter[$item['menu_id']] = 0;
                }
                $counter[$item['menu_id']] += $item['jumlah'];
            }
        }

        if (empty($counter)) return [];

        $maxValue = array_slice($counter, 0, 3, true);

        // Ambil menu id dengan penjualan terbanyak
        return array_keys($maxValue);
    }

    public function getAllMenus()
    {
        $user = Auth::user();
        $businessId = $user->id_business;

        $bestSellerIds = $this->getBestSellerMenuId($businessId);

        $menus = Menu::whereHas('category', function ($query) use ($businessId) {
            $query->where('business_id', $businessId);
        })->get()
            ->map(function ($menu) use ($bestSellerIds) {
                return [
                    'id' => $menu->id,
                    'nama' => $menu->nama,
                    'deskripsi' => $menu->deskripsi,
                    'harga' => $menu->harga,
                    'foto' => $menu->foto,
                    'business_id' => $menu->business_id,
                    'is_best_seller' => in_array($menu->id, $bestSellerIds),
                ];
            });

        return response()->json(['menus' => $menus]);
    }

    public function getMenus($kategoriId)
    {
        $user = Auth::user();
        $businessId = $user->id_business;
        $bestSellerIds = $this->getBestSellerMenuId($businessId);

        $menus = Menu::with('category')
            ->where('kategori_id', $kategoriId)
            ->whereHas('category', function ($query) use ($businessId) {
                $query->where('business_id', $businessId);
            })
            ->get()
            ->map(function ($menu) use ($bestSellerIds) {
                return [
                    'id' => $menu->id,
                    'nama' => $menu->nama,
                    'deskripsi' => $menu->deskripsi,
                    'harga' => $menu->harga,
                    'foto' => $menu->foto,
                    'business_id' => $menu->business_id,
                    'is_best_seller' => in_array($menu->id, $bestSellerIds),
                ];
            });

        return response()->json(['menus' => $menus]);
    }
}
