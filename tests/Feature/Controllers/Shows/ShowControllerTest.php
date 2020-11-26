<?php

namespace Tests\Feature\Controllers\Shows;

use App\Models\Shows\Show;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ShowControllerTest extends TestCase
{
    protected $base_route_name = Show::ROUTE_NAME;
    protected $base_view_path = Show::VIEW_PATH;
    protected $class_name = Show::class;

    /**
     * @test
     */
    public function anyone_can_see_the_index_view()
    {
        $this->withoutExceptionHandling();

        $this->getIndexViewResponse()
            ->assertViewIs($this->base_view_path . '.index');
    }

    /**
     * @test
     */
    public function a_user_can_get_a_paginated_collection_of_models()
    {
        $models = $this->class_name::factory()->count(3)->create();

        $this->getPaginatedCollection($models->first()->index_path);
    }

    /**
     * @test
     */
    public function anyone_can_see_the_show_view()
    {
        $this->withoutExceptionHandling();

        $model = $this->class_name::factory()->create();

        $this->getShowViewResponse(['show' => $model->id])
            ->assertViewIs($this->base_view_path . '.show')
            ->assertViewHas('model');
    }
}
