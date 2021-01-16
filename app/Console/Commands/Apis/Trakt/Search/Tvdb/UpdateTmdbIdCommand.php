<?php

namespace App\Console\Commands\Apis\Trakt\Search\Tvdb;

use App\Apis\Trakt\Trakt;
use App\Models\Shows\Show;
use Illuminate\Console\Command;

class UpdateTmdbIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apis:trakt:search:tvdb:updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Searches for tmdb ids.';

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
        $shows = Show::where('tmdb_id', 0)->get();

        $this->line('Show ID' . "\t\t" . 'TmDB ID');
        foreach ($shows as $show) {
            $trakt_show = Trakt::searchByTvdbId($show->tvdb_id);
            if (empty($trakt_show) || is_null($trakt_show[0]['show']['ids']['tmdb'])) {
                continue;
            }

            $this->line($show->id . "\t\t" . $trakt_show[0]['show']['ids']['tmdb']);
            $show->update([
                'tmdb_id' => $trakt_show[0]['show']['ids']['tmdb'],
            ]);
        }

        return 0;
    }
}
