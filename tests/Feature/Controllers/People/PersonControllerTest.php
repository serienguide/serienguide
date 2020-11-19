<?php

namespace Tests\Feature\Controllers\People;

use App\Models\People\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class PersonControllerTest extends TestCase
{
    protected $base_route_name = Person::ROUTE_NAME;
    protected $base_view_path = Person::VIEW_PATH;
    protected $class_name = Person::class;

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

        $this->getShowViewResponse(['person' => $model->id])
            ->assertViewIs($this->base_view_path . '.show')
            ->assertViewHas('model');
    }
}
