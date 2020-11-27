<?php

namespace Tests\Unit\Models\Shows\Seasons;

use App\Models\Shows\Seasons\Season;
use App\Models\Shows\Show;
use Tests\TestCase;

class SeasonTest extends TestCase
{
    const ID_PRETTY_LITTLE_LIARS = 31917;

    protected $class_name = Season::class;

    /**
     * @test
     */
    public function it_can_be_updated_from_tmdb()
    {
        $tmdb_id = self::ID_PRETTY_LITTLE_LIARS;
        $show = Show::factory()->create([
            'tmdb_id' => $tmdb_id,
        ]);
        $model = $this->class_name::factory()->create([
            'tmdb_id' => $tmdb_id,
            'show_id' => $show->id,
            'season_number' => 1,
        ]);
        $model->updateFromTmdb();
        $this->assertGreaterThan(0, $model->episodes()->count());
        $this->assertCount(1, $model->images);
        $this->assertGreaterThan(0, $model->episodes->first()->credits()->count());
        $this->assertCount(1, $model->episodes->first()->images);
    }
}
