<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Notification;
use Carbon\Carbon;

class CheckDueTransactions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->checkAndUpdateTransactions();

        return $next($request);
    }

    /**
     * Check and update transactions status
     */
    private function checkAndUpdateTransactions()
    {
        $today = Carbon::today();

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

            if (!$exists) {
                Notification::create([
                    'user_id' => $trx->user_id,
                    'title'   => 'Batas waktu pengembalian buku',
                    'message' => $message,
                    'type'    => 'info',
                    'is_read' => false,
                ]);
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
            if ($trx->status !== 'terlambat') {
                $daysLate = $today->diffInDays($trx->tanggal_jatuh_tempo);
                $trx->status = 'terlambat';
                $trx->save();

                $message = "Anda sudah terlambat mengembalikan buku '{$trx->book->judul}' selama {$daysLate} hari.";

                // Kirim notifikasi
                $exists = Notification::where('user_id', $trx->user_id)
                    ->where('message', $message)
                    ->whereDate('created_at', $today)
                    ->exists();

                if (!$exists) {
                    Notification::create([
                        'user_id' => $trx->user_id,
                        'title'   => 'Keterlambatan pengembalian buku',
                        'message' => $message,
                        'type'    => 'warning',
                        'is_read' => false,
                    ]);
                }
            }
        }
    }
}
