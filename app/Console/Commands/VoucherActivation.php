<?php

namespace App\Console\Commands;

use App\Models\Voucher;
use Illuminate\Console\Command;

class VoucherActivation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:voucher-activation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $vouchers = Voucher::where('start_date', '<=', now())
            ->where('is_active', false)
            ->update(['is_active' => true]);

        if ($vouchers) {
            $this->info("Activated {$vouchers} vouchers.");
        } else {
            $this->info("No vouchers to activate.");
        }
    }
}
