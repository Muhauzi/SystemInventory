<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\DendaReminderMail;
use App\Models\m_tagihan;
use Illuminate\Support\Facades\Log;

class SendDendaReminders extends Command
{
    protected $signature = 'send:denda-reminders';
    protected $description = 'Kirim pengingat denda kepada pengguna yang belum membayar denda';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('send:denda-reminders command started.');
    
        // Ambil semua tagihan belum lunas dan relasi user
        $tagihanList = m_tagihan::where('status_pembayaran', 'Belum Lunas')
            ->with('peminjaman.user')
            ->get();
    
        if ($tagihanList->isEmpty()) {
            $this->info("Tidak ada tagihan yang perlu diingatkan.");
            return;
        }
    
        // Group by id_user
        $groupedByUser = $tagihanList->groupBy(function ($tagihan) {
            return optional($tagihan->peminjaman)->id_user;
        });
    
        foreach ($groupedByUser as $id_user => $tagihanUser) {
            $firstTagihan = $tagihanUser->first();
            $user = optional($firstTagihan->peminjaman)->user;
    
            if (!$user) {
                $this->info("Pengguna tidak ditemukan untuk ID user: {$id_user}");
                continue;
            }
    
            if ($user->role !== 'partnership') {
                $this->info("Email tidak dikirim karena pengguna dengan ID: {$user->id} bukan partnership.");
                continue;
            }
    
            try {
                Mail::to($user->email)->send(new DendaReminderMail($tagihanUser, $user));
                $this->info("Email dikirim ke: {$user->email}");
            } catch (\Exception $e) {
                Log::error("Gagal mengirim email ke {$user->email}: " . $e->getMessage());
            }
        }
    }

}
