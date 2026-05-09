<?php

namespace App\Exports;

use App\Models\BarangJual;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class LaporanKeuanganExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        return BarangJual::with('user')
            ->whereBetween('tgl_jual', [$this->start, $this->end])
            ->orderBy('tgl_jual', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Tanggal',
            'Metode Pembayaran',
            'Total Harga',
            'Petugas',
        ];
    }

    public function map($penjualan): array
    {
        return [
            $penjualan->id,
            $penjualan->tgl_jual->format('d/m/Y H:i'),
            ucfirst($penjualan->metode_pembayaran),
            'Rp ' . number_format($penjualan->total_harga_jual, 0, ',', '.'),
            $penjualan->user->name ?? '-',
        ];
    }
}