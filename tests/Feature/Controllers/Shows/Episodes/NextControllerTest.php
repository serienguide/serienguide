<?php

namespace Tests\Feature\Controllers\Shows\Episodes;

use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Seasons\Season;
use App\Models\Shows\Show;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class NextControllerTest extends TestCase
{
    protected $class_name = Episode::class;

    /**
     * @test
     */
    public function it_can_get_the_next_episode()
    {
        $this->signIn();

        $show = Show::factory()->create([

        ]);
        $season = Season::factory()->create([
            'show_id' => $show->id,
            'season_number' => 1
        ]);
        $episodes = [];
        for($i = 1; $i <= 2; $i++) {
            $episodes[$i] = $this->class_name::factory()->create([
                'season_id' => $season->id,
                'show_id' => $show->id,
                'episode_number' => $i,
            ]);
        }

        $show->setAbsoluteNumbers();

        $response = $this->getJson($episodes[1]->next_path);
        $episode = $response->json();
        $this->assertEquals($episodes[2]->id, $episode['id']);

        $response = $this->getJson($episodes[2]->next_path);
        $this->assertEmpty($response->content());
    }

}
