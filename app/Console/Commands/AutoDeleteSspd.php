<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoDeleteSspd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dataAutoDelete:sspd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // return 0;
        \Log::info("Cron is working fine!");
        app('App\Http\Controllers\BPHTB\BphtbController')->autodelete_sspd();

    }
}
