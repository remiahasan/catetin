<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ManageMenuController;
use App\Http\Controllers\ManageStockController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KelolaBisnisController;
use App\Http\Controllers\Admin\UserVerificationController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ManageCategoryController;
use App\Http\Controllers\Pegawai\KeranjangController;
use App\Http\Controllers\Pegawai\PegawaiTransaksiController;
use App\Http\Controllers\Pegawai\RiwayatTransaksiController;


// Route::middleware('guest')->group(function () {
//     Route::get('/login', function () {
//         return view('auth.login');
//     });
// });


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/upload', [ProfileController::class, 'uploadPhoto'])->name('profile.upload');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile.view');
});


Route::middleware(['owner', 'auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/admin/verify-users', [UserVerificationController::class, 'index'])->name('admin.verify-users');
    Route::get('/admin/kelola-bisnis', [KelolaBisnisController::class, 'index'])->name('admin.kelola-bisnis');
    Route::get('/admin/kelola-bisnis/{id}', [KelolaBisnisController::class, 'kelola'])->name('admin.kelola-bisnis.kelola');

    // Management Users
    Route::post('/admin/unverify-users/{user}', [UserVerificationController::class, 'inverify'])->name('admin.inverify-user');
    Route::post('/admin/verify-users/{user}', [UserVerificationController::class, 'verify'])->name('admin.verify-user');
    Route::get('/admin/users/{id}/detail', [UserVerificationController::class, 'showDetail'])->name('admin.users.detail');
    Route::put('/admin/users/{id}', [UserVerificationController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserVerificationController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/admin/delete-users/{user}', [UserVerificationController::class, 'deleteUser'])->name('admin.delete-user');
    Route::post('/admin/add-users', [UserVerificationController::class, 'addUser'])->name('admin.add-user');

    // Management Menu
    Route::get('/admin/manage-menu', [ManageMenuController::class, 'index'])->name('admin.manage-menu');
    Route::delete('/admin/menus/{id}', [ManageMenuController::class, 'destroy'])->name('admin.menus.destroy');
    Route::put('/admin/menus/{id}', [ManageMenuController::class, 'update'])->name('admin.menus.update');
    Route::post('/admin/menu/add', [ManageMenuController::class, 'store'])->name('admin.menu.add');

    // Management Kategori
    Route::get('/admin/manage-category', [ManageCategoryController::class, 'index'])->name('admin.manage-category');
    Route::post('/admin/kategori/add', [ManageCategoryController::class, 'store'])->name('admin.category.add');
    Route::delete('/admin/kategori/{id}', [ManageCategoryController::class, 'destroy'])->name('admin.kategori.destroy');
    Route::put('/admin/kategori/{id}', [ManageCategoryController::class, 'update'])->name('admin.kategori.update');
    Route::get('/admin/kategori/by-business/{id}', [ManageMenuController::class, 'getKategoriByBusiness']);


    // Management Business
    Route::post('/admin/kelola-bisnis/add', [KelolaBisnisController::class, 'store'])->name('admin.kelola-bisnis.add');
    Route::put('/admin/kelola-bisnis/{id}', [KelolaBisnisController::class, 'update'])->name('admin.kelola-bisnis.update');
    Route::delete('/admin/kelola-bisnis/{id}', [KelolaBisnisController::class, 'destroy'])->name('admin.kelola-bisnis.destroy');

    // Management Stock
    Route::get('/admin/business/{id}/stocks', [LaporanController::class, 'getStocks']);
    Route::get('/admin/manage-stock/{id}', [ManageStockController::class, 'index'])->name('admin.manage-stock');
    Route::post('/admin/stock/add', [ManageStockController::class, 'store'])->name('admin.stock.add');
    Route::put('/admin/stock/{id}', [ManageStockController::class, 'update'])->name('admin.stock.update');
    Route::delete('/admin/stock/{id}', [ManageStockController::class, 'destroy'])->name('admin.stock.destroy');
    Route::get('/admin/laporan/{id}/detail', [LaporanController::class, 'detailLaporan'])->name('admin.laporan.detailLaporan');
    Route::get('/admin/laporan/pegawai/{id}', [LaporanController::class, 'laporanPegawai'])->name('admin.laporan.pegawai');
    Route::get('/admin/stock-history', [ManageStockController::class, 'stockHistory'])->name('admin.stock-history');

    //riwywatan stock

    // Management Jumlah Stock
    Route::get('/items/stock/add/jumlah-stock', [ManageStockController::class, 'addJumlahStok'])->name('admin.stock.add.jumlah_stok');
    Route::post('/items/stock/store/jumlah-stock', [ManageStockController::class, 'increaseStock'])->name('admin.stock.store.jumlah_stok');


    Route::get('/admin/laporan/{id}', [LaporanController::class, 'index'])->name('admin.laporan');
    Route::get('/admin/laporan/data', [LaporanController::class, 'getData'])->name('laporan.data');
});


Route::middleware(['pegawai', 'auth'])->group(function () {
    Route::get('/pegawai/update_stoke', [ManageStockController::class, 'pegawaiView'])->name('pegawai.update_stoke');
    Route::post('/pegawai/update_stock/store', [ManageStockController::class, 'reduceStock'])->name('pegawai.update.stock.store');
    // Semua menu sesuai business pegawai login
    Route::get('/pegawai/get-all-menus', [PegawaiTransaksiController::class, 'getAllMenus']);

    // Menu berdasarkan kategori
    Route::get('/pegawai/get-menus/{categoryId}', [PegawaiTransaksiController::class, 'getMenus']);

    Route::get('/pegawai/transaksi', [PegawaiTransaksiController::class, 'index'])->name('pegawai.transaksi.index');
    Route::post('/pegawai/keranjang/add', [KeranjangController::class, 'addToCart'])->name('pegawai.keranjang.add');
    Route::post('/pegawai/keranjang/update-quantity/{id}', [KeranjangController::class, 'updateQuantity'])->name('pegawai.keranjang.update-quantity');
    Route::delete('/pegawai/keranjang/{id}', [KeranjangController::class, 'removeFromCart'])->name('pegawai.keranjang.remove');
    Route::get('/pegawai/keranjang', [KeranjangController::class, 'viewCart'])->name('pegawai.keranjang');
    Route::post('/pegawai/keranjang/checkout', [KeranjangController::class, 'checkout'])->name('pegawai.keranjang.checkout');
    Route::get('/pegawai/riwayat-transaksi', [RiwayatTransaksiController::class, 'index'])->name('pegawai.riwayat-transaksi.index');
});

require __DIR__ . '/auth.php';
