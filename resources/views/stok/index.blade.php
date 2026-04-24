@extends('layouts.app')

@section('title', 'Stok Barang')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Stok Barang</h2>

    <div class="bg-white rounded-lg shadow-sm border border-gray-300 overflow-hidden">

        <div class="p-4 bg-gray-50 flex justify-end gap-3 border-b border-gray-300">
            <div class="relative">
                <select class="appearance-none border border-gray-400 rounded px-4 py-1.5 pr-8 bg-white text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option>Kategori</option>
                </select>
                <div class="absolute inset-y-0 right-2 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                </div>
            </div>

            <div class="relative">
                <input type="text" placeholder="Search" class="border border-gray-400 rounded px-3 py-1.5 pr-10 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                <div class="absolute inset-y-0 right-3 flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 text-sm uppercase">
                        <th class="border border-gray-300 p-2 w-10">
                            <input type="checkbox" class="rounded border-gray-400">
                        </th>
                        <th class="border border-gray-300 p-2 text-center w-12">No</th>
                        <th class="border border-gray-300 p-3 text-left">Nama Barang</th>
                        <th class="border border-gray-300 p-3 text-left">Deskripsi</th>
                        <th class="border border-gray-300 p-3 text-left">Harga</th>
                        <th class="border border-gray-300 p-3 text-left">Stok</th>
                        <th class="border border-gray-300 p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($barangs as $index => $barang)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="border border-gray-300 p-2 text-center">
                            <input type="checkbox" class="rounded border-gray-400">
                        </td>
                        <td class="border border-gray-300 p-2 text-center text-sm">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 p-3 text-sm font-medium text-gray-900">{{ $barang->nama_barang }}</td>
                        <td class="border border-gray-300 p-3 text-sm text-gray-500 italic">
                            {{ $barang->deskripsi ?? '-' }}
                        </td>
                        <td class="border border-gray-300 p-3 text-sm">
                            Rp {{ number_format($barang->harga ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="border border-gray-300 p-3 text-sm">
                            {{ $barang->stok ?? 0 }}
                        </td>
                        <td class="border border-gray-300 p-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="#" class="text-blue-600 hover:text-blue-800 p-1" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="#" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="border border-gray-300 p-8 text-center text-gray-500 italic">
                            Belum ada data barang tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
