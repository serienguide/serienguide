<?php

namespace Tests\Unit\Models\Commments;

use App\Models\Commments\Comment;
use Tests\TestCase;

class CommentTest extends TestCase
{
    protected $class_name = Comment::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = $this->class_name::factory()->create();
        $route_parameter = [
            'comment' => $model->id,
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
    public function it_()
    {
        $dates = [
            '2021-01-01T05:43:00Z' => '2021-01-01T06:43:00+01:00',
            '2021-05-01T05:43:00Z' => '2021-05-01T07:43:00+02:00',
        ];

        foreach ($dates as $utc => $berlin) {
            $date = new \Carbon\Carbon($utc);
            dump($date->format('c'), $date->dst);
            $date->setTimeZone('Europe/Berlin');
            dump($date->format('c'), $date->dst);
        }
    }
}
