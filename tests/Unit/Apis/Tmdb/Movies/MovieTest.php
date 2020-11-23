<?php

namespace Tests\Unit\Apis\Tmdb\Movies;

use App\Apis\Tmdb\Movies\Movie;
use Tests\TestCase;

class MovieTest extends TestCase
{
    const ID_MAD_MAX = 76341;

    protected $class_name = Movie::class;

    /**
     * @test
     */
    public function it_finds_a_movie()
    {
        $model = Movie::find(self::ID_MAD_MAX);
        $this->assertEquals(150000000, $model->budget);
        $this->assertEquals('https://www.warnerbros.com/movies/mad-max-fury-road', $model->homepage);
        $this->assertEquals('Bei seinen Reisen durch die postapokalyptische Ödnis der Zukunft wird Max von den Schergen des Warlords Immortan Joe gefangen genommen und als lebende Blutkonserve für den schwerkranken Krieger Nux verwendet. Als Imperator Furiosa, eine wichtige Handlangerin Joes, den Herrscher hintergeht, indem sie seine fünf Frauen befreit und während einer Handelsmission mit ihnen an Bord ihres Kriegslasters flieht, ruft der Warlord sein Heer zur Verfolgung auf. Zu den Kriegern gehört auch Nux, der Max zur Blutversorgung an seinen Wagen schnallt. Doch während des ersten Gefechts zwischen Joes Leuten und Furiosa kann sich Max befreien und bildet von da eine Zweckgemeinschaft mit Furiosa und den Frauen, während sie weiter unerbittlich von den Truppen Joes gejagt werden...', $model->overview);
        $this->assertEquals('2015-05-13', $model->released_at->format('Y-m-d'));
        $this->assertEquals(378858340, $model->revenue);
        $this->assertEquals(120, $model->runtime);
        $this->assertEquals('Released', $model->status);
        $this->assertEquals('Was für ein schöner Tag. Nur der Wahnsinn überlebt.', $model->tagline);
        $this->assertEquals('Mad Max: Fury Road', $model->title);
        $this->assertEquals('Mad Max: Fury Road', $model->title_en);
        $this->assertEquals(2015, $model->year);
        dump($model);
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
