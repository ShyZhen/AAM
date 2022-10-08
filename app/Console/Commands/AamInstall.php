<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class AamInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aam:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'some init for AAM';

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
     * 初始化项目
     *
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (Schema::hasTable('users')) {
            $this->error('===== ERROR: You had installed! =====');
        } else {
            $this->info('===== AAM Install Start =====');
            $this->call('key:generate');
            $this->call('storage:link');
            $this->call('migrate');
            $this->call('db:seed');
//            $this->call('config:cache');
//            $this->call('route:cache');
            $this->info('===== AAM Install End =====');
        }
    }
}
