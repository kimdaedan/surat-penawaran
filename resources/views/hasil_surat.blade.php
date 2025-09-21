<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Surat Penawaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #e9ecef; }
        .surat { background-color: white; max-width: 800px; margin: 40px auto; padding: 40px; border: 1px solid #dee2e6; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .kop-surat { text-align: center; border-bottom: 2px solid black; padding-bottom: 15px; margin-bottom: 30px; }
        .kop-surat h2, .kop-surat p { margin: 0; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>
<body>

<div class="surat">
    <div class="kop-surat">
        <h2>PT. MAJU BERSAMA</h2>
        <p>Jl. Pembangunan No. 123, Batam, Indonesia | Telp: (0778) 123456</p>
    </div>

    <div class="d-flex justify-content-between">
        <div>
            <p>Nomor: {{ $data['nomor_surat'] }}</p>
            <p>Lampiran: -</p>
            <p>Perihal: Penawaran Produk</p>
        </div>
        <div>
            <p>Batam, {{ date('d F Y', strtotime($data['tanggal_surat'])) }}</p>
        </div>
    </div>

    <hr>

    <div>
        <p>Kepada Yth.</p>
        <p><strong>{{ $data['nama_klien'] }}</strong></p>
        <p>Di {{ $data['alamat_klien'] }}</p>
    </div>

    <div class="mt-4">
        <p>Dengan hormat,</p>
        <p>Melalui surat ini, kami ingin mengajukan penawaran untuk produk/jasa sebagai berikut:</p>
    </div>

    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>Nama Produk/Jasa</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $data['produk'] }}</td>
                <td>{{ $data['jumlah'] }}</td>
                <td>Rp {{ number_format($data['harga'], 0, ',', '.') }}</td>
                <td><strong>Rp {{ number_format($data['jumlah'] * $data['harga'], 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="mt-4">
        <p>Demikian surat penawaran ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
    </div>

    <div class="mt-5 text-end">
        <p>Hormat kami,</p>
        <br><br><br>
        <p><strong>(Nama Anda)</strong><br>Marketing Manager</p>
    </div>

    <div class="mt-5 text-center">
        <a href="/" class="btn btn-primary">Kembali ke Form</a>
    </div>

</div>

</body>
</html>