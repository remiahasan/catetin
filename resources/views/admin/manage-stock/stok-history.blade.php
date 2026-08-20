@extends('components.layout.OwnerLayout.body.index')
@section('title', 'Riwayat Stok')
@section('admin')
    <x-table :headers="[
        'Waktu' => 'created_at',
        'Nama' => 'nama',
        'Business' => 'business_name',
        'Pengubah' => 'user_name',
        'Status' => 'status',
        'Jumlah' => 'jumlah',
    ]" :rows="$riwayatStok" title="Riwayat Stok" :total="$riwayatStok->count()" :business_id="$business->id" :perPage="$perPage"
        buttonAction="togglePopup('popup-add-kategori')" />

@endsection
