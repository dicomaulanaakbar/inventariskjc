@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- 🔷 Ringkasan -->
<div class="bg-white p-4 rounded shadow mb-6">

    <h2 class="text-lg font-semibold mb-4">Ringkasan Barang</h2>

    <div class="grid grid-cols-4 gap-4">

        <div class="bg-gray-100 p-4 rounded shadow text-center">
            <h3 class="text-xl font-bold">10</h3>
            <p>Total barang</p>
        </div>

        <div class="bg-gray-100 p-4 rounded shadow text-center">
            <h3 class="text-xl font-bold">1</h3>
            <p>Stok kurang</p>
        </div>

        <div class="bg-gray-100 p-4 rounded shadow text-center">
            <h3 class="text-xl font-bold">2</h3>
            <p>Barang masuk</p>
        </div>

        <div class="bg-gray-100 p-4 rounded shadow text-center">
            <h3 class="text-xl font-bold">2</h3>
            <p>Barang keluar</p>
        </div>

    </div>

</div>

<!-- 🔷 Tabel -->
<div class="bg-white p-4 rounded shadow">

    <div class="flex justify-between mb-3">
        <select class="border p-2 rounded">
            <option>Kategori</option>
        </select>

        <input type="text" placeholder="Search"
               class="border p-2 rounded">
    </div>

    <table class="w-full border">
        <thead class="bg-gray-200">
            <tr>
                <th class="border p-2">No</th>
                <th class="border p-2">Nama Barang</th>
                <th class="border p-2">Deskripsi</th>
                <th class="border p-2">Harga</th>
                <th class="border p-2">Stok</th>
                <th class="border p-2">Aksi</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td class="border p-2">1</td>
                <td class="border p-2">Laptop</td>
                <td class="border p-2">Gaming</td>
                <td class="border p-2">10.000.000</td>
                <td class="border p-2">5</td>
                <td class="border p-2">Edit</td>
            </tr>
        </tbody>
    </table>

</div>

@endsection