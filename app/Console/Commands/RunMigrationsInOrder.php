<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunMigrationsInOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migration_order:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the migrations in order';

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
        // Order of migrations
        Artisan::call('migrate', ['--path' => '/database/migrations/2021_05_19_173331_create_user_roles_table.php'], $this->getOutput());
        Artisan::call('migrate', ['--path' => '/database/migrations/2021_05_19_172740_create_article_categories_table.php'], $this->getOutput());

        // Run the rest
        Artisan::call('migrate', [], $this->getOutput());
    }
}
