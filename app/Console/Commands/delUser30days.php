<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;

class delUser30days extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'month:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will remove trash users more than 30 days';

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
        DB::table('tasks')->whereNotNull('deleted_at')->whereDate('created_at', '<', Carbon::now()->subDays(30))->delete();
        echo "Operation done";
    }
}
