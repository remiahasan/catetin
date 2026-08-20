<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Size;
use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ManageMenuController extends Controller
{
    public function index(Request $request)
    {
        $usahaId = $request->input('usaha_id');
        $kategoriId = $request->input('kategori_id');
        $datanama = "menu";

        $businesses = $this->getAllBusinesses();
        $categories = $this->getCategoriesByBusiness($usahaId);
        $menus = $this->getFilteredMenus($usahaId, $kategoriId);

        return view('admin.manage-menu.index', compact('businesses', 'categories', 'menus', 'usahaId', 'kategoriId', 'datanama'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:business,id', // cek nama tabelnya
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|exists:categories,id',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,heic|max:5120',
        ]);

        $fotoPath = 'img/illustrations/no-image.png'; // default image

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaMenu = Str::slug($request->nama);
            $namaFile = $namaMenu . '-' . time() . '.' . $foto->getClientOriginalExtension();

            // simpan file
            $foto->move(public_path('img/upload'), $namaFile);
            $fotoPath = 'img/upload/' . $namaFile; // update path jika ada file
        }

        // simpan ke DB
        Menu::create([
            'business_id' => $request->business_id,
            'nama' => $request->nama,
            'kategori_id' => $request->kategori_id,
            'harga' => $request->harga,
            'foto' => $fotoPath,
        ]);

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'business_id' => 'required|exists:business,id',
            'kategori_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,heic|max:5120',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->business_id = $request->business_id;
        $menu->kategori_id = $request->kategori_id;
        $menu->nama = $request->nama;
        $menu->harga = $request->harga;

        if ($request->hasFile('foto')) {
            // hapus foto lama kecuali default "no-image.png"
            if (
                $menu->foto &&
                $menu->foto !== 'img/illustrations/no-image.png' &&
                File::exists(public_path($menu->foto))
            ) {
                File::delete(public_path($menu->foto));
            }

            $foto = $request->file('foto');
            $namaMenu = Str::slug($request->nama);
            $namaFile = $namaMenu . '-' . time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('img/upload'), $namaFile);
            $menu->foto = 'img/upload/' . $namaFile;
        }

        $menu->save();

        return redirect()->back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        if ($menu->foto && File::exists(public_path($menu->foto))) {
            File::delete(public_path($menu->foto));
        }

        $menu->delete();

        return redirect()->back()->with('success', 'Menu dan fotonya berhasil dihapus.');
    }

    private function getAllBusinesses()
    {
        return Business::get();
    }

    private function getCategoriesByBusiness($usahaId = null)
    {
        if ($usahaId) {
            return Category::where('business_id', $usahaId)->get();
        }

        return Category::get();
    }

    private function getFilteredMenus($usahaId = null, $kategoriId = null)
    {
        return Menu::with(['business', 'category'])
            ->when($usahaId, function ($query) use ($usahaId) {
                $query->where('business_id', $usahaId);
            })
            ->when($kategoriId, function ($query) use ($kategoriId) {
                $query->where('kategori_id', $kategoriId);
            })

            ->get();
    }
}
