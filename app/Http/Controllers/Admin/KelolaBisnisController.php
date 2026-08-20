<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class KelolaBisnisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $business = Business::withCount(['menus', 'users'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->get();

        return view('admin.kelola-bisnis.index', compact('business'));
    }

    public function kelola($id)
    {
        $business = Business::withCount(['menus', 'stocks', 'users', 'categories'])->findOrFail($id);

        $perPage = 5;
        $currentPage = request()->get('page', 1);

        // =========================
        // PAGINATION STOCKS
        // =========================
        $allStocks = $business->stocks;
        $totalStocks = $allStocks->count();
        $stocks = $allStocks->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // =========================
        // PAGINATION MENUS
        // =========================
        $allMenus = $business->menus;
        $totalMenus = $allMenus->count();
        $menus = $allMenus->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return view('admin.kelola-bisnis.kelola', compact(
            'business',
            'stocks',
            'totalStocks',
            'menus',
            'totalMenus',
            'perPage',
            'currentPage'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
        ]);

        Business::create([
            'name' => $request->name,
            'lokasi' => $request->lokasi
        ]);

        return redirect()->back()->with('success', 'Bisnis berhasil diperbarui.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
        ]);

        $business = Business::findOrFail($id);
        $business->name = $request->name;
        $business->lokasi = $request->lokasi;
        $business->save();

        return redirect()->back()->with('success', 'Bisnis berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $business = Business::findOrFail($id);

        $business->users()->delete();
        $business->menus()->delete();
        $business->stocks()->delete();
        $business->transaksis()->delete();
        $business->categories()->delete();

        $business->delete();

        return redirect()->back()->with('success', 'Bisnis berhasil dihapus.');
    }
}
