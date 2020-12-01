<?php

namespace Tests\Unit\Models\Watched;

use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Seasons\Season;
use App\Models\Shows\Show;
use App\Models\Watched\Watched;
use Tests\TestCase;

class WatchedTest extends TestCase
{
    protected $class_name = Watched::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = $this->class_name::factory()->create();
        $route_parameter = [
            'type' => $model->watchable_type::ROUTE_NAME,
            'model' => $model->watchable_id,
            'watched' => $model->id,
        ];

        $route = strtok(route($this->class_name::ROUTE_NAME . '.index', $route_parameter), '?');
        $this->assertEquals($route, $this->class_name::indexPath($model->toArray()));

        $route = strtok(route($this->class_name::ROUTE_NAME . '.create', $route_parameter), '?');
        $this->assertEquals($route, $model->create_path);

        $route = route($this->class_name::ROUTE_NAME . '.show', $route_parameter);
        $this->assertEquals($route, $model->path);

        $route = route($this->class_name::ROUTE_NAME . '.edit', $route_parameter);
        $this->assertEquals($route, $model->edit_path);

        $route = strtok(route($this->class_name::ROUTE_NAME . '.index', $route_parameter), '?');
        $this->assertEquals($route, $model->index_path);
    }

    /**
     * @test
     */
    public function it_gets_the_latest_episodes_by_show()
    {
        $episodes_count = 3;

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
            for($i = 1; $i <= $episodes_count; $i++) {
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
            $show->SetCounts();
            $shows[] = $show;
        }

        $latest_episodes = Watched::getLatestEpisodeIdsByShow($this->user->id, 0);
        $this->assertCount(2, $latest_episodes);

        $next_episodes = Watched::getNextEpisodes($this->user->id, 0);
        $this->assertCount(2, $next_episodes);

        dump($next_episodes->first());

    }
}
