<?php

namespace Tests\Unit\Apis\Tmdb;

use App\Apis\Tmdb\Search;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_search_movies()
    {
        $models = Search::movies('The Avengers');
        dump($models);
    }

    /**
     * @test
     */
    public function it_can_search_showss()
    {
        $models = Search::shows('scrubs');
        dump($models);
    }
}
