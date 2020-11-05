<?php

namespace Tests\Unit\Models\Lists;

use App\Models\Lists\Item;
use App\Models\Lists\Listing;
use Tests\TestCase;

class ListingTest extends TestCase
{
    protected $class_name = Listing::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = $this->class_name::factory()->create();
        $route_parameter = [
            'list' => $model->id,
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
    public function it_has_many_items()
    {
        $model = $this->class_name::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->assertCount(0, $model->items);

        $items = Item::factory()->count(3)->create([
            'list_id' => $model->id,
        ]);

        $this->assertCount(3, $model->refresh()->items);
    }

    /**
     * @test
     */
    public function it_belongs_to_a_user()
    {
        $model = $this->class_name::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->assertEquals($this->user->id, $model->user->id);
    }
}
