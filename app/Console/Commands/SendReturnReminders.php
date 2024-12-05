<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReturnReminderMail;
use Carbon\Carbon;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\User;

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
        $peminjaman = Peminjaman::where('status', 'Dipinjam')->get();
        $detail = new DetailPeminjaman();
        $now = Carbon::now();
        foreach ($peminjaman as $data) {
            $dueDate = Carbon::parse($data->tanggal_tenggat);
            $barang = $detail->getDetail($data->id_peminjaman);
            $diff = $now->diffInDays($dueDate, false);
            if ($diff < 3) { //
                $user = User::find($data->id_user);
                Mail::to($user->email)->send(new ReturnReminderMail($data, $barang, $user));   
            }
        }

        $this->info('Return reminders have been sent successfully to ' . count($peminjaman) . ' users');
    }


}
