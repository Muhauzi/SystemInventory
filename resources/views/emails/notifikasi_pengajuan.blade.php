<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Notifikasi Pengajuan Peminjaman</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            color: #111827;
            padding: 20px;
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 0.5rem;
            padding: 2rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .header {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #1f2937;
        }

        .label {
            font-weight: 600;
            color: #374151;
        }

        .value {
            color: #4b5563;
        }

        .footer {
            margin-top: 2rem;
            font-size: 0.875rem;
            color: #9ca3af;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">📩 Pengajuan Peminjaman Inventaris</div>

        <p>Halo Admin,</p>
        <p><span class="label">{{ $namaPemohon }}</span> telah mengajukan permohonan peminjaman barang inventaris dengan detail sebagai berikut:</p>

        <table style="width: 100%; margin-top: 1rem;">
            <tr>
                <td class="label">Tanggal Pengajuan:</td>
                <td class="value">{{ \Carbon\Carbon::parse($tanggalPengajuan)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Mulai:</td>
                <td class="value">{{ \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Selesai:</td>
                <td class="value">{{ \Carbon\Carbon::parse($tanggalSelesai)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Status Pengajuan:</td>
                <td class="value">{{ ucfirst($statusPengajuan) }}</td>
            </tr>
            <tr>
                <td class="label">Alasan Peminjaman:</td>
                <td class="value">{{ $alasan }}</td>
            </tr>
            <tr>
                <td class="label">Surat Pengantar:</td>
                <td class="value">
                    @if($suratPengantar)
                    <a href="{{ url('storage/surat_pengantar/' . $suratPengantar) }}" style="color: #3b82f6; text-decoration: underline;">Lihat Surat</a>
                    @else
                    Tidak ada
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Barang yang Dipinjam:</td>
                <td class="value">
                    @if($listBarang)
                        <ul style="margin: 0; padding-left: 1.5rem;">
                            @foreach($listBarang as $item)
                                <li>{{ $item['id_barang'] }} | {{ $item['nama_barang'] }}</li>
                            @endforeach
                        </ul>
                    @else
                        Tidak ada barang yang dipinjam.
                    @endif
                </td>
            </tr>
        </table>

        <p style="margin-top: 1.5rem;">Silakan periksa sistem untuk meninjau dan menindaklanjuti pengajuan ini.</p>

        <div class="footer">
            Email ini dikirim otomatis oleh sistem inventaris. Mohon tidak membalas email ini.
        </div>
    </div>
</body>

</html>