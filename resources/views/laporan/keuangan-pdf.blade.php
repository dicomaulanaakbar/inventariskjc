<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .periode { margin-bottom: 15px; }
        .total { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Keuangan</h2>
        <p>Karim Jaya Computer</p>
    </div>
    <div class="periode">
        <strong>Periode:</strong> {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
    </div>
    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>Metode Pembayaran</th>
                <th>Total Harga</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $jual)
            <tr>
                <td>{{ $jual->id }}</td>
                <td>{{ $jual->tgl_jual->format('d/m/Y H:i') }}</td>
                <td>{{ ucfirst($jual->metode_pembayaran) }}</td>
                <td>Rp {{ number_format($jual->total_harga_jual, 0, ',', '.') }}</td>
                <td>{{ $jual->user->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="total">
        <p>Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        <p>Total Pengeluaran: Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        <p>Laba Kotor: Rp {{ number_format($laba, 0, ',', '.') }}</p>
    </div>
</body>
</html>