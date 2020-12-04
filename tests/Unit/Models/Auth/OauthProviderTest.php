<?php

namespace Tests\Unit\Models\Auth;

use App\Models\Auth\OauthProvider;
use Tests\TestCase;

class OauthProviderTest extends TestCase
{
    protected $class_name = OauthProvider::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = $this->class_name::factory()->create();
        $route_parameter = [
            'provider' => $model->id,
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
    public function it_knows_its_routes()
    {
        $model = $this->class_name::factory()->create([

        ]);
        $this->assertEquals(url('login/facebook'), $model->redirect_route);
        $this->assertEquals(url('login/facebook/callback'), $model->callback_route);
    }
}
