<?php

namespace App\Console\Commands\Apis\Glotz\Shows;

use App\Models\Shows\Show;
use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apis:glotz:shows:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updated shows from the glotz api';

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
        $updates = \App\Apis\Glotz\Show::updated();
        foreach ($updates['shows'] as $key => $glotz_show) {
            $show = Show::where('tvdb_id', $glotz_show['tvdb_id'])->first();

            if (is_null($show)) {
                continue;
            }

            $show->updateFromGlotz();
        }

        return 0;
    }
}
