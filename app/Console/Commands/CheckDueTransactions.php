<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Notification;
use Carbon\Carbon;

class CheckDueTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:check-due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alias untuk perintah app:cek-keterlambatan agar kompatibel';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // delegate to the single source of truth command
        $this->call('app:cek-keterlambatan');
        return 0;
    }
}
