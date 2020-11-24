<?php

namespace Tests\Feature\Controllers\Lists;

use App\Models\Lists\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ListingControllerTest extends TestCase
{
    protected $base_route_name = Listing::ROUTE_NAME;
    protected $base_view_path = Listing::VIEW_PATH;
    protected $class_name = Listing::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = $this->class_name::factory()->create();
        $routes = [
            'create' => $model->create_path,
            'store' => $model->index_path,
            'edit' => $model->edit_path,
            'update' => $model->path,
            'destroy' => $model->path,
        ];
        $this->guestsCanNotAccess($routes);
    }

    /**
     * @test
     */
    public function anyone_can_see_the_index_view()
    {
        $this->getIndexViewResponse([
            'user' => $this->user->id,
        ])
            ->assertViewIs($this->base_view_path . '.index');
    }

    /**
     * @test
     */
    public function anyone_can_get_a_paginated_collection_of_models()
    {
        $this->withoutExceptionHandling();

        $models = $this->class_name::factory()->count(3)->create([
            'user_id' => $this->user->id,
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
            'name' => 'New Model'
        ];

        $attributes = [
            'user_id' => $this->user->id,
        ];

        $this->post($this->class_name::indexPath($attributes), $data)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas((new $this->class_name)->getTable(), $data);
    }

    /**
     * @test
     */
    public function anyone_can_see_the_show_view()
    {
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
        $this->withoutExceptionHandling();

        $this->signIn();

        $model = $this->createModel([
            'user_id' => $this->user->id
        ]);

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

        $model = $this->createModel([
            'user_id' => $this->user->id
        ]);

        $this->signIn();

        $data = [
            'name' => 'updated list',
            'description' => 'description',
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

        $model = $this->createModel([
            'user_id' => $this->user->id
        ]);

        $this->deleteModel($model)
            ->assertRedirect();
    }
}
