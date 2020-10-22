<?php

namespace Tests\Feature\Controllers\Movies;

use App\Models\Movies\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    protected $base_route_name = 'movies';
    protected $base_view_path = 'movies';
    protected $className = Movie::class;

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
    public function a_user_can_get_a_collection_of_models()
    {
        $models = Movie::factory()->count(3)->create();

        $this->getPaginatedCollection();
    }

    /**
     * @test
     */
    public function anyone_can_see_the_show_view()
    {
        $this->withoutExceptionHandling();

        $model = Movie::factory()->create();

        $this->getShowViewResponse(['movie' => $model->id])
            ->assertViewIs($this->base_view_path . '.show')
            ->assertViewHas('model');
    }
}
