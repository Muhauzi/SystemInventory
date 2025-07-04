<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan Kerusakan Barang</title>
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
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff;
        }
        p {
            margin: 10px 0;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            background: #f9f9f9;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-top: 10px;
        }
        a.button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        a.button:hover {
            background-color: #0056b3;
        }

        /* Mobile view */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
                margin: 10px;
            }
            h1 {
                font-size: 1.5em;
            }
            p, ul li {
                font-size: 1em;
            }
            a.button {
                padding: 10px 15px;
                font-size: 1em;
            }
        }

        /* Desktop view */
        @media (min-width: 601px) {
            .container {
                padding: 20px;
                margin: 20px auto;
            }
            h1 {
                font-size: 2em;
            }
            p, ul li {
                font-size: 1.2em;
            }
            a.button {
                padding: 10px 20px;
                font-size: 1.2em;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Tagihan Kerusakan Barang</h1>
        <p>Yth. {{ $data['name'] }},</p>
        <p>Kami ingin memberitahukan bahwa Anda memiliki tagihan kerusakan barang sebagai berikut:</p>
        <ul>
            <li>ID Peminjaman: {{ $data['id_peminjaman'] }}</li>
            <li>Nama Barang: {{ $data['nama_barang'] }}</li>
            <li>Total Biaya: Rp {{ number_format($data['total_tagihan'], 0, ',', '.') }}</li>
            <li>Deskripsi Kerusakan: {{ $data['deskripsi_kerusakan'] }}</li>
        </ul>
        <p>Berikut adalah nota perbaikan barang:</p>
        <img src="{{ asset('storage/nota_perbaikan/' . $data['nota_perbaikan']) }}" alt="Nota Perbaikan">
        <p><strong>Catatan:</strong> Anda tidak dapat meminjam barang lain sebelum menyelesaikan tagihan ini.</p>
        <p>Silahkan melakukan pembayaran melalui website kami pada menu tagihan kerusakan.</p>
        <p>Untuk melakukan pembayaran, silahkan klik tombol di bawah ini:</p>
        <a href="{{ url('/user/tagihan_kerusakan') }}" class="button">Bayar Sekarang</a>
        <p>Mohon untuk segera melakukan pembayaran.</p>
        <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi kami.</p>
        <p>Terima kasih atas perhatian dan kerjasamanya.</p>
        <p>Salam,</p>
        <p>Tim Inventaris</p>
    </div>
</body>

</html>

