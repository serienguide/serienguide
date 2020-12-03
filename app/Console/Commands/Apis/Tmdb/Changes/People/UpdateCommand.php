<?php

namespace App\Console\Commands\Apis\Tmdb\Changes\People;

use App\Apis\Tmdb\Changes\Change;
use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apis:tmdb:changes:people:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets all changes from tmdb and updates existing people';

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
        Change::movies();

        return 0;
    }
}
