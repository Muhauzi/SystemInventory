<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Pengembalian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            margin: 10px 0;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            background: #eee;
            margin: 5px 0;
            padding: 10px;
            border-radius: 4px;
        }
        small {
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pengingat: Batas Waktu Pengembalian</h1>
        <p>Yth. {{ $user->name }},</p>
        @if (!$data)
            <p>Tidak ada data yang tersedia.</p>
        @endif
        <p>Ini adalah pengingat bahwa barang yang Anda pinjam harus dikembalikan pada <strong>{{ $data->tgl_tenggat }}</strong>.</p>
        <p>Detail Barang:</p>
        <ul>
            @foreach ($barang as $item)
                <li>{{ $item->id_barang }} - {{ $item->nama_barang }}</li>
            @endforeach     
        </ul>
        @if (!$barang)
            <p>Tidak ada barang yang tersedia.</p>
        @endif
        <p>Harap kembalikan barang tersebut pada atau sebelum tanggal jatuh tempo.</p>
        <p>Terima kasih.</p>
        <p>Hormat kami,</p>
        <p>Sistem Informasi Inventaris Barang</p>
        <p><small>Ini adalah pesan otomatis. Mohon tidak membalas email ini.</small></p>
        <p><small>&copy; 2021 Sistem Manajemen Inventaris. Semua hak dilindungi undang-undang.</small></p>
    </div>
</body>
</html>