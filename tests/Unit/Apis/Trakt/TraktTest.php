<?php

namespace Tests\Unit\Apis\Trakt;

use App\Apis\Trakt\Http;
use App\Apis\Trakt\Trakt;
use Tests\TestCase;

class TraktTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_the_last_activities()
    {
        Trakt::setAccessToken('cffad84404011cd4c9615695e9bd57be94305fc3bf438816be92be7de09b4daf');
        $data = Trakt::lastActivities();
        dump($data);
    }

    /**
     * @test
     */
    public function it_can_refresh_the_access_token()
    {
        $data = Trakt::refreshToken('');
        dump($data);
    }

    /**
     * @test
     */
    public function it_can_get_the_watched_movies()
    {
        Trakt::setAccessToken('cffad84404011cd4c9615695e9bd57be94305fc3bf438816be92be7de09b4daf');
        $data = Trakt::watched('shows');
        dump($data);
    }

    /**
     * @test
     */
    public function it_can_search_by_tvdb_id()
    {
        $data = Trakt::searchByTvdbId(367070);
        dump($data);
    }
}
