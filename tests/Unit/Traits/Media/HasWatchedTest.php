<?php

namespace Tests\Unit\Traits\Media;

use App\Models\Movies\Movie;
use App\Models\Shows\Episodes\Episode;
use App\Models\User;
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
}
