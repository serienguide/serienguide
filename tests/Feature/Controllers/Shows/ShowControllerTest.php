<?php

namespace Tests\Feature\Controllers\Shows;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use {{ namespacedModel }};

class ShowControllerTest extends TestCase
{
    protected $base_route_name = {{ model }}::ROUTE_NAME;
    protected $base_view_path = {{ model }}::VIEW_PATH;
    protected $class_name = {{ model }}::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = $this->class_name::factory()->create();

        $routes = [
            'index' => $model->index_path,
            'create' => $model->create_path,
            'store' => $model->index_path,
            'show' => $model->path,
            'edit' => $model->edit_path,
            'update' => $model->path,
            'destroy' => $model->path,
        ];
        $this->guestsCanNotAccess($routes);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = $this->class_name::factory()->create();

        $this->a_user_can_not_see_models_from_a_different_user($modelOfADifferentUser);
        $this->a_different_user_gets_a_403('get', $modelOfADifferentUser->index_path);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_index_view()
    {
        $this->getIndexViewResponse()
            ->assertViewIs($this->base_view_path . '.index');
    }

    /**
     * @test
     */
    public function a_user_can_get_a_paginated_collection_of_models()
    {
        $this->signIn();

        $models = $this->class_name::factory()->count(3)->create([

        ]);

        $this->getPaginatedCollection($models->first()->index_path);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $data = [

        ];

        $this->post($this->class_name::indexPath(), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->class_name)->getTable(), $data);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_show_view()
    {
        $this->withoutExceptionHandling();

        $model = $this->createModel();

        $this->getShowViewResponse($model->route_parameter)
            ->assertViewIs($this->base_view_path . '.show')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $model = $this->createModel();

        $this->getEditViewResponse($model->route_parameter)
            ->assertViewIs($this->base_view_path . '.edit')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model()
    {
        $this->withoutExceptionHandling();

        $model = $this->createModel();

        $this->signIn();

        $data = [

        ];

        $response = $this->put($model->path, $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas($model->getTable(), [
            'id' => $model->id,
        ] + $data);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_model()
    {
        $this->signIn();

        $model = $this->createModel();

        $this->deleteModel($model)
            ->assertRedirect();
    }
}
