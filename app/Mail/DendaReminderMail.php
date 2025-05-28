<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DendaReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $data;
    public $user;


    public function __construct($tagihanUser, $user)
    {
        $this->tagihanUser = $tagihanUser; // Collection of tagihan
        $this->user = $user;
    }

    // /**
    //  * Build the message.
    //  */
    public function build()
    {
        return $this->subject('Pengingat Tagihan Denda')
                ->view('emails.denda_reminder')
                ->with(['tagihanUser' => $this->tagihanUser, 'user' => $this->user]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Denda Reminder Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.denda_reminder',
            with: ['data' => $this->data],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
