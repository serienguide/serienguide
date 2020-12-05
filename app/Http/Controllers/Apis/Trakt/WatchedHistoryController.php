<?php

namespace App\Http\Controllers\Apis\Trakt;

use App\Apis\Trakt\Trakt;
use App\Http\Controllers\Controller;
use App\Models\Auth\OauthProvider;
use App\Models\Movies\Movie;
use App\Models\Shows\Seasons\Season;
use App\Models\Shows\Show;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class WatchedHistoryController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movies\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, OauthProvider $provider)
    {
        if ($provider->provider_type != 'trakt') {
            abort(404);
        }

        $provider->load('user');

        Trakt::setAccessToken($provider->token);
        $last_activities = Trakt::lastActivities();
        $last_watched_episodes = new Carbon($last_activities['episodes']['watched_at']);
        $last_watched_movies = new Carbon($last_activities['movies']['watched_at']);
        $last_watched = max($last_watched_episodes, $last_watched_movies);

        if ($last_watched < $provider->synced_at) {
            return redirect($provider->index_path)
                ->with('status', [
                    'type' => 'success',
                    'text' => 'Es ist bereits alles auf dem aktuellen Stand.'
                ]);
        }

        Artisan::queue('apis:trakt:sync:watched', [
            'provider' => $provider->id
        ]);

        return redirect($provider->index_path)
            ->with('status', [
                'type' => 'success',
                'text' => 'Filme & Folgen werden im Hintergrund aktualisiert.'
            ]);
    }

}
