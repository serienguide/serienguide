<?php

namespace Tests\Unit\Apis\Tmdb\Shows;

use App\Apis\Tmdb\Shows\Show;
use Tests\TestCase;

class ShowTest extends TestCase
{
    const ID_GAME_OF_THRONES = 1399;

    protected $class_name = Show::class;

    /**
     * @test
     */
    public function it_finds_a_show()
    {
        $model = Show::find(self::ID_GAME_OF_THRONES);
        $this->assertNotNull('https://www.warnerbros.com/movies/mad-max-fury-road', $model->homepage);
        $this->assertNotNull($model->overview);
        $this->assertEquals('2011-04-17', $model->first_aired_at->format('Y-m-d'));
        $this->assertEquals('2019-05-19', $model->last_aired_at->format('Y-m-d'));
        $this->assertEquals(60, $model->runtime);
        $this->assertEquals('Ended', $model->status);
        $this->assertNotNull($model->tagline);
        $this->assertEquals('Game of Thrones', $model->name);
        $this->assertEquals('Game of Thrones', $model->name_en);
        $this->assertEquals(2011, $model->year);
        // dump($model->facebook);
    }
}
