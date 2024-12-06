{{
    dd($user, $inventaris, $tagihan, $laporan_kerusakan)
}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan Kerusakan Barang</title>
</head>
<body>
    <!-- Email body -->
    <div class="container">
        <h1>Tagihan Kerusakan Barang</h1>
        <p>Yth. {{ $user['nama'] }},</p>
        <p>Kami ingin memberitahukan bahwa Anda memiliki tagihan kerusakan barang sebagai berikut:</p>
        <ul>
            <li>Nama Barang: {{ $inventaris['nama_barang'] }}</li>
            <li>Total Biaya: Rp {{ number_format($tagihan['total_tagihan'], 0, ',', '.') }}</li>
        </ul>
        <p>Mohon untuk segera melakukan pembayaran.</p>
        <a href="{{ $tagihan->payment_url }}">Bayar Tagihan</a>
        <p>Terima kasih atas perhatian dan kerjasamanya.</p>
        <p>Salam,</p>
        <p>Tim Keuangan</p>
    </div>
</body>
</html>