<?php

namespace Tests\Unit\Apis\Tmdb;

use App\Apis\Tmdb\Trending;
use Tests\TestCase;

class TrendingTest extends TestCase
{
    const ID_MAD_MAX = 76341;
    const ID_TRIBUTE_VON_PANEM = 70160;

    protected $class_name = Change::class;

    /**
     * @test
     */
    public function it_can_get_the_trending_movies()
    {
        $models = Trending::movies();
    }

    /**
     * @test
     */
    public function it_can_get_the_trending_people()
    {
        $models = Trending::people();
    }

    /**
     * @test
     */
    public function it_can_get_the_trending_shows()
    {
        $models = Trending::shows();
    }
}
