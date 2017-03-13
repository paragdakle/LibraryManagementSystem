<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Fine;
use App\BookLoan;
use Config;

class Fines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:fines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate fines for users with book due date less than current date';

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
     * @return mixed
     */
    public function handle()
    {
        Fine::where('paid', 0)->delete();
        $today_timestamp = strtotime('today');
        echo($today_timestamp);
        $book_loans = BookLoan::whereNull('date_in')->where('due_date', '<', $today_timestamp)->get();
        var_dump($book_loans);
        foreach($book_loans as $book_loan) {
            $time_diff = $today_timestamp - $book_loan->due_date;
            $hours = floor($time_diff / (60 * 60 * 24));
            var_dump($hours);
            if($hours > 0) {
                $fine_amount = $hours * Config::get('constants.fine_per_day');
                echo($fine_amount);
                $fine = new Fine;
                $fine->loan_id = $book_loan->loan_id;
                $fine->fine_amt = $fine_amount;
                $fine->paid = false;
                $fine->save();
            }
        }
    }
}
