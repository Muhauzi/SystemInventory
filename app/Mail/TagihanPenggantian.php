<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TagihanPenggantian extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $tagihan, $dataPeminjam, $laporan_kerusakan;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $tagihan, $dataPeminjam)
    {
        $this->user = $user;
        $this->tagihan = $tagihan;
        $this->dataPeminjam = $dataPeminjam;
    }


    public function build()
    {
        return $this->view('emails.tagihan_penggantian')
            ->subject('Tagihan Penggantian')
            ->with([
                'user' => $this->user,
                'tagihan' => $this->tagihan,
                'dataPeminjam' => $this->dataPeminjam
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tagihan Penggantian',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tagihan_penggantian',
            with: [
                'user' => $this->user,
                'tagihan' => $this->tagihan,
                'dataPeminjam' => $this->dataPeminjam
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
        return [];
    }
}
