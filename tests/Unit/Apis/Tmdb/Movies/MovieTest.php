<?php

namespace Tests\Unit\Apis\Tmdb\Movies;

use App\Apis\Tmdb\Movies\Movie;
use Tests\TestCase;

class MovieTest extends TestCase
{
    const ID_MAD_MAX = 76341;
    const ID_TRIBUTE_VON_PANEM = 70160;

    protected $class_name = Movie::class;

    /**
     * @test
     */
    public function it_finds_a_movie()
    {
        $model = Movie::find(self::ID_MAD_MAX);
        $this->assertGreaterThan(0, $model->budget);
        $this->assertEquals('https://www.warnerbros.com/movies/mad-max-fury-road', $model->homepage);
        $this->assertNotNull($model->overview);
        $this->assertEquals('2015-05-13', $model->released_at->format('Y-m-d'));
        $this->assertGreaterThan(0, $model->revenue);
        $this->assertGreaterThan(0, $model->runtime);
        $this->assertEquals('Released', $model->status);
        $this->assertNotNull('Was für ein schöner Tag. Nur der Wahnsinn überlebt.', $model->tagline);
        $this->assertEquals('Mad Max: Fury Road', $model->name);
        $this->assertEquals('Mad Max: Fury Road', $model->name_en);
        $this->assertEquals(2015, $model->year);
        // dump($model->facebook);
    }

    /**
     * @test
     */
    public function it_finds_a_movie_with_collection()
    {
        $model = Movie::find(self::ID_TRIBUTE_VON_PANEM);
        // dump($model);
    }

    /**
     * @test
     */
    public function it_can_get_its_watch_providers()
    {
        $model = new Movie([
            'id' => self::ID_MAD_MAX,
        ]);
        $providers = $model->getWatchProviders();
        $this->assertArrayHasKey('DE', $providers);
    }
}
