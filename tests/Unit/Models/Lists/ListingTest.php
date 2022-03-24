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
            'user' => $model->user_id,
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

    /**
     * @test
     */
    public function it_creates_a_unique_slug_per_user()
    {
        $model = Listing::factory()->create([
            'name' => 'New List',
        ]);
        $this->assertEquals('new-list', $model->slug);

        $model = $this->user->lists()->create([
            'name' => 'New List',
        ]);
        $this->assertEquals('new-list', $model->slug);

        $model = $this->user->lists()->create([
            'name' => 'New List',
        ]);
        $this->assertEquals('new-list-1', $model->slug);
        $model_list_1 = $model;

        $model = $this->user->lists()->create([
            'name' => 'New List',
        ]);
        $this->assertEquals('new-list-2', $model->slug);

        $model_list_1->update([
            'name' => 'New List',
            'description' => 'test',
        ]);
        $this->assertEquals('new-list-1', $model_list_1->refresh()->slug);
    }

    /**
     * @test
     */
    public function it_deletes_its_items_on_delete()
    {
        $model = Listing::factory()->create([
            'name' => 'New List',
        ]);

        $model->delete();
    }
}
