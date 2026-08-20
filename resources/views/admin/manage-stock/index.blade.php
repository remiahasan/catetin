@extends('components.layout.OwnerLayout.body.index')
@section('title', 'Kelola Stok ' . $business_name->name)
@section('admin')
    <div class="grid grid-cols-1 -mx-3">

        <x-form-stok :stocks="$stocks" mode="tambah" />


    </div>
@endsection
