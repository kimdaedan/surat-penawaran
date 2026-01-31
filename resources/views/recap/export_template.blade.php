<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table { width: 100%; border-collapse: collapse; font-family: sans-serif; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        .header { text-align: center; margin-bottom: 20px; }
        .total { font-weight: bold; background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>RINCIAN REKAP BIAYA</h2>
        <p>Klien: {{ $recap->offer->nama_klien }} | Tanggal: {{ $recap->created_at->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr style="background-color: #eee;">
                <th>Tgl Keluar</th>
                <th>Deskripsi</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recap->items as $item)
            <tr>
                <td>{{ $item->tanggal_item ?? '-' }}</td>
                <td>{{ $item->material }} ({{ $item->detail }})</td>
                <td>{{ $item->qty }}</td>
                <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="4">TOTAL PENAWARAN</td>
                <td>{{ number_format($recap->total_penawaran_klien, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td colspan="4">TOTAL MODAL</td>
                <td>{{ number_format($recap->total_pengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr class="total" style="color: {{ $recap->margin >= 0 ? 'green' : 'red' }}">
                <td colspan="4">NET PROFIT (LABA)</td>
                <td>{{ number_format($recap->margin, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>