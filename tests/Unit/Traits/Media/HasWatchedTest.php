<?php

namespace Tests\Unit\Traits\Media;

use App\Models\Movies\Movie;
use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Seasons\Season;
use App\Models\Shows\Show;
use App\Models\User;
use App\Models\Watched\Watched;
use Tests\TestCase;

class HasWatchedTest extends TestCase
{
    /**
     * @test
     */
    public function a_movie_can_be_marked_as_watched()
    {
        $user = User::factory()->create();

        $model = Movie::factory()->create();
        $watched = $model->watchedBy($this->user);
        $this->assertCount(1, $model->watched);
        $this->assertCount(1, $model->watchedByUser($this->user->id)->get());
        $this->assertCount(0, $model->watchedByUser($user->id)->get());
    }

    /**
     * @test
     */
    public function an_episode_can_be_marked_as_watched()
    {
        $user = User::factory()->create();

        $model = Episode::factory()->create();
        $watched = $model->watchedBy($this->user);
        $this->assertCount(1, $model->watched);
        $this->assertCount(1, $model->watchedByUser($this->user->id)->get());
        $this->assertCount(0, $model->watchedByUser($user->id)->get());
    }

    /**
     * @test
     */
    public function a_season_can_be_marked_as_watched()
    {
        $episodes_count = 3;

        $show = Show::factory()->create([

        ]);
        $season = Season::factory()->create([
            'id' => 25,
            'show_id' => $show->id,
            'season_number' => 1
        ]);
        $episodes = [];
        for($i = 1; $i <= $episodes_count; $i++) {
            $episodes[$i] = Episode::factory()->create([
                'season_id' => $season->id,
                'show_id' => $show->id,
                'episode_number' => $i,
            ]);
        }
        $show->setAbsoluteNumbers();
        $show->SetCounts();

        $season->watchedBy($this->user);
        $this->assertCount($episodes_count, $season->watchedByUser($this->user->id)->get());
        $this->assertEquals($episodes_count, $season->watchedByUser($this->user->id)->distinct()->count('watchable_id'));

    }
}
