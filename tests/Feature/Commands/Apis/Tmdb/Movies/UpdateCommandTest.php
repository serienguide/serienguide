<?php

namespace Tests\Feature\Commands\Apis\Tmdb\Movies;

use App\Models\Movies\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UpdateCommandTest extends TestCase
{
    const ID_MAD_MAX = 76341;

    /**
     * @test
     */
    public function it_can_update_a_movie()
    {
        $model = Movie::factory()->create([
            'tmdb_id' => self::ID_MAD_MAX,
        ]);

        Artisan::call('apis:tmdb:movies:update', [
            'id' => $model->id
        ]);

        $model = $model->refresh();

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
    }


}
