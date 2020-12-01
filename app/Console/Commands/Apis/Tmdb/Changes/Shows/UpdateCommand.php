<?php

namespace App\Console\Commands\Apis\Tmdb\Changes\Shows;

use App\Apis\Tmdb\Changes\Change;
use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Seasons\Season;
use App\Models\Shows\Show;
use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apis:tmdb:changes:shows:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets all changes from tmdb and updates existing shows';

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
        $changes = Change::shows();

        foreach ($changes as $change) {

            $tmdb_show_id = $change['id'];

            if (empty($tmdb_show_id)) {
                continue;
            }

            $show = Show::where('tmdb_id', $tmdb_show_id)->first();

            if (is_null($show)) {
                continue;
            }

            dump($show->id);

            $show_changes = \App\Apis\Tmdb\Shows\Show::changes($tmdb_show_id);
            $show_changes_count = count($show_changes['changes']);
            $season_changes_key = array_search('season', array_column($show_changes['changes'], 'key'));

            if ($season_changes_key === false || $show_changes_count > 1) {
                $show->updateFromTmdb();
            }

            if ($season_changes_key === false) {
                continue;
            }

            foreach ($show_changes['changes'][$season_changes_key]['items'] as $tv_change_season) {

                $tmdb_season_id = $tv_change_season['value']['season_id'];
                $season_number = $tv_change_season['value']['season_number'];

                if ($season_number == 0) {
                    continue;
                }

                if ($tv_change_season['action'] == 'destroyed') {

                    Season::where('tmdb_id', $tmdb_season_id)->delete();
                    continue;

                }

                $season_changes = \App\Apis\Tmdb\Shows\Seasons\Season::changes($tmdb_season_id);
                $season_changes_count = count($season_changes['changes']);
                $episode_changes_key = array_search('episode', array_column($season_changes['changes'], 'key'));

                $season = $show->seasons()->where('tmdb_id', $tmdb_season_id)->first();

                if (is_null($season)) {
                    $season = $show->seasons()->where('season_number', $season_number)->first();
                }

                if (is_null($season)) {
                    $season = $show->seasons()->withTrashed()->updateOrCreate([
                        'season_number' => $season_number,
                    ], [
                        'tmdb_id' => $tmdb_season_id,
                    ] + [
                        'deleted_at' => null,
                    ]);
                }

                if ($episode_changes_key === false || $season_changes_count > 1) {

                    $season->updateFromTmdb();

                }

                if ($episode_changes_key === false) {
                    continue;
                }

                foreach ($season_changes['changes'][$episode_changes_key]['items'] as $episode_changes) {

                    $tmdb_episode_id = $episode_changes['value']['episode_id'];
                    $episode_number = $episode_changes['value']['episode_number'];

                    if ($episode_number == 0) {
                        continue;
                    }

                    if ($episode_changes['action'] == 'destroyed') {

                        Episode::where('tmdb_id', $tmdb_episode_id)->delete();
                        continue;

                    }

                    $episode = $season->episodes()->where('tmdb_id', $tmdb_episode_id)->first();

                    if (is_null($episode)) {
                        $episode = $season->episodes()->where('episode_number', $episode_number)->first();
                    }

                    if (is_null($episode)) {
                        $episode = $season->episodes()->withTrashed()->updateOrCreate([
                            'episode_number' => $episode_number,
                        ], [
                            'tmdb_id' => $tmdb_episode_id,
                            'show_id' => $season->show_id,
                        ] + [
                            'deleted_at' => null,
                        ]);
                    }

                    $episode->updateFromTmdb();

                }

            }

            $show->setAbsoluteNumbers();
            $show->setCounts();
        }

        return 0;
    }
}
