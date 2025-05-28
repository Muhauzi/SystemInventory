<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Pengembalian</title>
    <style>
        /* Global Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f5f7fa;
            color: #444;
            line-height: 1.6;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #2a9d8f;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 500;
        }
        .content {
            padding: 24px;
        }
        .content p {
            margin-bottom: 16px;
            font-size: 16px;
        }
        .list {
            list-style: none;
            margin-bottom: 16px;
        }
        .list li {
            background-color: #f1f3f5;
            padding: 12px;
            border-radius: 4px;
            font-size: 15px;
            margin-bottom: 8px;
        }
        .total {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 24px;
        }
        .button-wrap {
            text-align: center;
            margin-bottom: 24px;
        }
        .button {
            background-color: #2a9d8f;
            color: #ffffff;
            text-decoration: none;
            display: inline-block;
            padding: 12px 24px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
        }
        .footer {
            background-color: #f1f3f5;
            padding: 16px 24px;
            font-size: 13px;
            color: #888;
            text-align: center;
        }
        .footer a {
            color: #2a9d8f;
            text-decoration: none;
        }
        @media screen and (max-width: 480px) {
            .content p, .list li, .total {
                font-size: 14px;
            }
            .button {
                width: 100%;
                padding: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pengingat: Tagihan Denda</h1>
        </div>
        <div class="content">
            <p>Yth. <strong>{{ $user->name }}</strong>,</p>
            <p>Ini adalah pengingat bahwa Anda memiliki tagihan denda yang harus dibayarkan.</p>
            @foreach ($tagihanUser as $tagihan)
                <p>Tagihan ID: {{ $tagihan->id }}, Jumlah: {{ number_format($tagihan->jumlah_tagihan) }}</p>
            @endforeach
            <div class="button-wrap">
                <a href="https://inventaris-pst.biz.id/user/tagihan" class="button">Lihat Tagihan</a>
            </div>
            <p>Harap diperhatikan bahwa jika Anda belum melunasi denda keterlambatan, Anda tidak dapat mengajukan peminjaman hingga tagihan Anda dilunasi.</p>
            <p>Untuk informasi lebih lanjut, kunjungi <a href="https://inventaris-pst.biz.id">inventaris-pst.biz.id</a>.</p>
            <p>Jika Anda memiliki pertanyaan, silakan hubungi kami.</p>
            <p>Terima kasih atas perhatian Anda.</p>
            <p>Hormat kami,</p>
            <p><strong>Sistem Informasi Inventaris Barang</strong></p>
        </div>
        <div class="footer">
            <small>Catatan: Ini adalah email otomatis, mohon tidak membalas.</small>
        </div>
    </div>
</body>
</html>
