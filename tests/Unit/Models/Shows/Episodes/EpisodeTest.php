<?php

namespace Tests\Unit\Models\Shows\Episodes;

use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Seasons\Season;
use App\Models\Shows\Show;
use Tests\TestCase;

class EpisodeTest extends TestCase
{
    const ID_PRETTY_LITTLE_LIARS = 31917;

    protected $class_name = Episode::class;

    /**
     * @test
     */
    public function it_can_be_updated_from_tmdb()
    {
        $tmdb_id = self::ID_PRETTY_LITTLE_LIARS;
        $show = Show::factory()->create([
            'tmdb_id' => $tmdb_id,
        ]);
        $season = Season::factory()->create([
            'show_id' => $show->id,
            'season_number' => 1
        ]);
        $model = $this->class_name::factory()->create([
            'season_id' => $season->id,
            'show_id' => $show->id,
            'episode_number' => 1,
        ]);
        $model->updateFromTmdb();
        $model = $model->refresh();
        $this->assertGreaterThan(0, $model->credits()->count());
        $this->assertCount(1, $model->images);
        $this->assertNotNull($model->name);
        $this->assertNotNull($model->tmdb_id);
    }
}
