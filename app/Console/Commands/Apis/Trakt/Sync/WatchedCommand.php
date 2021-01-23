<?php

namespace App\Console\Commands\Apis\Trakt\Sync;

use App\Apis\Trakt\Trakt;
use App\Models\Auth\OauthProvider;
use App\Models\Movies\Movie;
use App\Models\Shows\Show;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class WatchedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apis:trakt:sync:watched {provider=0}';

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
        if ($this->argument('provider')) {
            $providers = OauthProvider::with([
                    'user',
                ])->where('id', $this->argument('provider'))
                ->get();
        }
        else {
            $providers = OauthProvider::with([
                    'user',
                ])->where('provider_type', 'trakt')
                ->whereNotNull('synced_at')
                ->get();
        }

        foreach ($providers as $key => $provider) {
            $this->updateProvider($provider);
        }

        return 0;
    }

    protected function updateProvider(OauthProvider $provider)
    {
        $this->info('syncing ' . $provider->user->name . ' (Provider ID: ' . $provider->id . ')');
        $provider->refresh();

        Trakt::setAccessToken($provider->token);

        $movie_sync_count = $this->movies($provider->user);
        $episode_sync_count = $this->episodes($provider->user);

        $provider->update([
            'synced_at' => now(),
        ]);

        $this->line('Movies: ' . $movie_sync_count);
        $this->line('Episodes: ' . $episode_sync_count);

        $provider->user->notify(new \App\Notifications\Apis\Tract\Sync\Watched([
            'movie_sync_count' => $movie_sync_count,
            'episode_sync_count' => $episode_sync_count,
        ]));
    }

    protected function movies(User $user) : int
    {
        $sync_count = 0;
        $trakt_watched_movies = Trakt::watched('movies');
        foreach ($trakt_watched_movies as $key => $trakt_watched) {
            if (! Arr::has($trakt_watched['movie']['ids'], 'tmdb')) {
                continue;
            }

            $movie = Movie::updateOrCreate([
                'tmdb_id' => $trakt_watched['movie']['ids']['tmdb']
            ], [
                'name' => $trakt_watched['movie']['title'],
                'name_en' => $trakt_watched['movie']['title'],
                'imdb_id' => $trakt_watched['movie']['ids']['imdb'],
            ]);

            if ($movie->wasRecentlyCreated) {
                Artisan::queue('apis:tmdb:movies:update', [
                    'id' => $movie->id
                ]);
            }

            $watched_count = $movie->watchedByUser($user->id)->count();
            $not_watched_count = $trakt_watched['plays'] - $watched_count;
            if ($watched_count >= $trakt_watched['plays']) {
                continue;
            }

            if ($not_watched_count == 1) {
                $movie->watchedBy($user, [
                    'watched_at' => $this->parseWatchedAt($trakt_watched['last_watched_at']),
                ]);
                $sync_count++;
                continue;
            }

            $trakt_watched_history = Trakt::watchedHistory('movies', null, $trakt_watched['movie']['ids']['trakt']);
            for ($i=0; $i < $not_watched_count; $i++) {
                $movie->watchedBy($user, [
                    'watched_at' => $this->parseWatchedAt($trakt_watched_history[$i]['watched_at']),
                ]);
                $sync_count++;
            }
        }

        return $sync_count;
    }

    protected function episodes(User $user) : int
    {
        $sync_count = 0;
        $trakt_watched_episodes = Trakt::watched('shows');
        foreach ($trakt_watched_episodes as $trakt_watched) {
            $show = $this->findShow($trakt_watched['show']);
            if ($show->wasRecentlyCreated && $show->tmdb_id) {
                Artisan::queue('apis:tmdb:shows:update', [
                    'id' => $show->id
                ]);
            }
            foreach ($trakt_watched['seasons'] as $trakt_season) {
                $season = $show->seasons()->updateOrCreate([
                    'season_number' => $trakt_season['number'],
                ]);
                foreach ($trakt_season['episodes'] as $trakt_episode) {
                    $episode = $season->episodes()->updateOrCreate([
                        'episode_number' => $trakt_episode['number'],
                    ], [
                        'show_id' => $season->show_id,
                    ]);

                    $watched_count = $episode->watchedByUser($user->id)->count();
                    $not_watched_count = $trakt_episode['plays'] - $watched_count;
                    if (($watched_count >= $trakt_episode['plays']) || ($not_watched_count == 0)) {
                        continue;
                    }

                    $episode->watchedBy($user, [
                        'watched_at' => $this->parseWatchedAt($trakt_episode['last_watched_at']),
                    ]);
                    $sync_count++;

                }
            }
        }

        return $sync_count;
    }

    protected function findShow(array $trakt_show) : Show
    {
        if ($trakt_show['ids']['tmdb']) {
            $attributes = [
                'tmdb_id' => $trakt_show['ids']['tmdb'],
            ];
        }
        elseif ($trakt_show['ids']['tmdb']) {
            $attributes = [
                'tvdb_id' => $trakt_show['ids']['tvdb'],
            ];
        }
        return Show::updateOrCreate($attributes, [
            'name' => $trakt_show['title'],
            'name_en' => $trakt_show['title'],
            'imdb_id' => $trakt_show['ids']['imdb'],
            'tmdb_id' => $trakt_show['ids']['tmdb'],
            'tvdb_id' => $trakt_show['ids']['tvdb'],
        ]);
    }

    protected function parseWatchedAt(string $watched_at) : Carbon
    {
        $carbon = new Carbon($watched_at, 'GMT');
        $carbon->setTimeZone(new \DateTimeZone('Europe/Berlin'));

        return $carbon;
    }
}
