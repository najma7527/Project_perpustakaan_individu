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

    protected $description = 'Cek batas waktu peminjaman dan status keterlambatan transaksi, sekaligus kirim notifikasi';

    public function handle()
    {
        $today = Carbon::today();
        $createdCount = 0;
        $updatedCount = 0;

        /*
         * 1. Ingatkan pengguna yang jatuh tempo hari ini
         */
        $dueToday = Transaction::whereNull('tanggal_pengembalian')
            ->whereDate('tanggal_jatuh_tempo', $today)
            ->with('book')
            ->get();

        foreach ($dueToday as $trx) {
            $message = 'Batas waktu pengembalian buku "' . $trx->book->judul . '" adalah hari ini. Silakan kembalikan segera.';

            // Pastikan tidak mengirim duplikat dalam satu hari
            $exists = Notification::where('user_id', $trx->user_id)
                ->where('message', $message)
                ->whereDate('created_at', $today)
                ->exists();

            if (! $exists) {
                Notification::create([
                    'user_id' => $trx->user_id,
                    'title'   => 'Batas waktu pengembalian buku',
                    'message' => $message,
                    'type'    => 'info',
                    'is_read' => false,
                ]);
                $createdCount++;
            }
        }

        /*
         * 2. Perbarui status keterlambatan dan kirim notifikasi harian
         */
        $late = Transaction::whereNull('tanggal_pengembalian')
            ->whereDate('tanggal_jatuh_tempo', '<', $today)
            ->with('book')
            ->get();

        foreach ($late as $trx) {
            $daysLate = $today->diffInDays($trx->tanggal_jatuh_tempo);

            if ($trx->status !== 'terlambat') {
                $trx->status = 'terlambat';
                $trx->save();
                $updatedCount++;
            }

            $message = "Anda sudah terlambat mengembalikan buku '{$trx->book->judul}' selama {$daysLate} hari.";

            $exists = Notification::where('user_id', $trx->user_id)
                ->where('message', $message)
                ->whereDate('created_at', $today)
                ->exists();

            if (! $exists) {
                Notification::create([
                    'user_id' => $trx->user_id,
                    'title'   => 'Keterlambatan pengembalian buku',
                    'message' => $message,
                    'type'    => 'warning',
                    'is_read' => false,
                ]);
                $createdCount++;
            }
        }

        $this->info("Selesai. {$createdCount} notifikasi dibuat, {$updatedCount} status transaksi diperbarui.");
        return 0;
    }
}
