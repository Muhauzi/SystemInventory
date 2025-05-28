<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReturnReminderMail;
use Carbon\Carbon;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendReturnReminders extends Command
{
    protected $signature = 'send:return-reminders';
    protected $description = 'Send return reminders to users who have not returned their borrowed items';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('send:return-reminders command started.');
        $peminjaman = Peminjaman::where('status', 'Dipinjam')->get();
        $detail = new DetailPeminjaman();
        $now = Carbon::now();
        foreach ($peminjaman as $data) {
            $dueDate = Carbon::parse($data->tanggal_tenggat);
            $barang = $detail->getDetail($data->id_peminjaman);
            $tenggat = $data->tgl_tenggat;  
            $now = $now->startOfDay(); // Set $now ke awal hari
            $dueDate = Carbon::parse($tenggat)->startOfDay(); // Set $dueDate ke awal hari

            $diff = $now->diffInDays($dueDate, false);
            $this->info("Tanggal sekarang: {$now}, Tanggal tenggat: {$dueDate}, Selisih hari: {$diff} untuk peminjaman ID: {$data->id_peminjaman}");

            $user = User::find($data->id_user);
            if ($user->role !== 'partnership') {
                $this->info("Email tidak dikirim ke: {$user->email} karena bukan partnership.");
                continue;
            }

            if ($diff <= 3) { // jika tenggat 5 hari atau kurang dari 3 hari, email akan dikirim
                Mail::to($user->email)->send(new ReturnReminderMail($data, $barang, $user));
                $this->info("Email dikirim ke: {$user->email}");
            } else {
                $this->info("Email tidak dikirim ke: {$user->email}");
            }
        }
    }
}
