<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Traits\Utilities\LukeFileGenerator;
use Illuminate\Console\Command;

class GenerateLukeFile extends Command
{
    use LukeFileGenerator;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:lukefile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Receipt based on Luke File Format';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $transactions = Transaction::where('transaction_type', 2)->get();
        foreach($transactions as $transaction){
            $this->generateFile($transaction->purchase_order_id);
        }

    }
}
