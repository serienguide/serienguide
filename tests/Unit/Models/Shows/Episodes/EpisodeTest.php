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

    /**
     * @test
     */
    public function it_can_get_the_next_episode()
    {
        $show = Show::factory()->create([

        ]);
        $season = Season::factory()->create([
            'show_id' => $show->id,
            'season_number' => 1
        ]);
        $episodes = [];
        for($i = 1; $i <= 3; $i++) {
            $episodes[$i] = $this->class_name::factory()->create([
                'season_id' => $season->id,
                'show_id' => $show->id,
                'episode_number' => $i,
            ]);
        }

        $show->setAbsoluteNumbers();

        $episode = $episodes[1]->refresh();
        $this->assertEquals($episode->episode_number, 1);
        $this->assertEquals($episode->absolute_number, 1);

        $next_episode = Episode::nextByAbsoluteNumber($episode->show_id, $episode->absolute_number)->first();
        $this->assertEquals($next_episode->episode_number, 2);
        $this->assertEquals($next_episode->absolute_number, 2);

        $episode = $episodes[2]->refresh();
        $this->assertEquals($episode->episode_number, 2);
        $this->assertEquals($episode->absolute_number, 2);

        $next_episode = Episode::nextByAbsoluteNumber($episode->show_id, $episode->absolute_number)->first();
        $this->assertEquals($next_episode->episode_number, 3);
        $this->assertEquals($next_episode->absolute_number, 3);

        $episode = $episodes[3]->refresh();
        $this->assertEquals($episode->episode_number, 3);
        $this->assertEquals($episode->absolute_number, 3);

        $next_episode = Episode::nextByAbsoluteNumber($episode->show_id, $episode->absolute_number)->first();
        $this->assertNull($next_episode);
    }

    /**
     * @test
     */
    public function it_can_scope_the_next_episodes_to_watch()
    {
        $shows = [];
        $episodes = [];
        for ($show_number = 1; $show_number <= 3; $show_number++) {
            $show = Show::factory()->create([

            ]);
            $season = Season::factory()->create([
                'show_id' => $show->id,
                'season_number' => 1
            ]);
            $episodes[$show_number] = [];
            for($i = 1; $i <= 3; $i++) {
                $episodes[$i] = Episode::factory()->create([
                    'season_id' => $season->id,
                    'show_id' => $show->id,
                    'episode_number' => $i,
                ]);
                if ($i == $show_number) {
                    $episodes[$i]->watchedBy($this->user);
                }
            }
            $show->setAbsoluteNumbers();
            $shows[] = $show;
        }

        $next_episodes = Episode::nextEpisodes($this->user->id)->get();
        $this->assertCount(2, $next_episodes);
        $this->assertEquals(3, $next_episodes->get(0)->absolute_number);
        $this->assertEquals(2, $next_episodes->get(1)->absolute_number);

    }

    /**
     * @test
     */
    public function it_knows_its_label()
    {
        $this->assertEquals('Folge', $this->class_name::label(1));
        $this->assertEquals('Folgen', $this->class_name::label(2));
        $this->assertEquals('Folgen', $this->class_name::label());
    }

    /**
     * @test
     */
    public function it_knows_its_next_path()
    {
        $model = $this->class_name::factory()->create();
        $this->assertEquals(route('episodes.next', [
            'episode' => $model->id,
        ]), $model->next_path);
    }
}
