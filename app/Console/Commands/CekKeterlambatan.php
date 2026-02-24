<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\Book;
use Carbon\Carbon;

class CekKeterlambatan extends Command
{
    protected $signature = 'app:cek-keterlambatan';

    protected $description = 'Cek dan update status keterlambatan transaksi';

    public function handle()
    {
        $today = Carbon::today();

        $terlambat = Transaction::where('status', 'belum_dikembalikan')
            ->whereDate('tanggal_jatuh_tempo', '<', $today)
            ->with('book') // ambil relasi buku
            ->get();

        $totalUpdate = 0;

        foreach ($terlambat as $trx) {

            // Update status jadi terlambat
            $trx->update([
                'status' => 'terlambat'
            ]);

            // Cek apakah notifikasi sudah pernah dibuat hari ini
            $sudahAda = Notification::where('user_id', $trx->user_id)
                ->where('pesan', 'like', "%{$trx->book->judul}%")
                ->whereDate('created_at', $today)
                ->exists();

        if (!$sudahAda) {
            Notification::create([
                'user_id' => $trx->user_id,
                'pesan' => "Anda terlambat mengembalikan buku '{$trx->book->judul}'."
            ]);
        }

        $totalUpdate++;
    }

    $this->info('Cek keterlambatan selesai. Total diperbarui: ' . $totalUpdate);
}

}
