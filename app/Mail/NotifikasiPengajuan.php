<?php

namespace App\Mail;

use Dflydev\DotAccessData\Data;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class NotifikasiPengajuan extends Mailable
{
    use Queueable, SerializesModels;



    /**
     * Create a new message instance.
     */
    public function __construct(
        protected $data
    ) { 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notifikasi: Pengajuan Peminjaman Baru',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notifikasi_pengajuan',
            with: [
                'namaPemohon' => $this->data->nama_peminjam,
                'tanggalPengajuan' => $this->data->tanggal_pengajuan,
                'tanggalMulai' => $this->data->tanggal_mulai,
                'tanggalSelesai' => $this->data->tanggal_selesai,
                'statusPengajuan' => $this->data->status_pengajuan,
                'alasan' => $this->data->alasan,
                'suratPengantar' => $this->data->surat_pengantar,
                'listBarang' => $this->data->barang,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $path = public_path('surat_pengantar/' . $this->data->surat_pengantar);

        if (file_exists($path)) {
            return [
                Attachment::fromPath($path)
                    ->as('surat_pengantar_peminjaman_' . $this->data->nama_peminjam . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
