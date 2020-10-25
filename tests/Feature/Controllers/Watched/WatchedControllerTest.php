<?php

namespace Tests\Feature\Controllers\Watched;

use App\Models\Movies\Movie;
use App\Models\Watched\Watched;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class WatchedControllerTest extends TestCase
{
    protected $base_route_name = Watched::ROUTE_NAME;
    protected $base_view_path = Watched::VIEW_PATH;
    protected $class_name = Watched::class;

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
    }

    /**
     * @test
     */
    public function a_user_can_get_a_paginated_collection_of_models()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $parent = $this->createParent();

        $models = $this->class_name::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'watchable_id' => $parent->id,
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

        $parent = Movie::factory()->create();

        $data = [
            //
        ];

        $attributes = [
            'watchable_type' => Movie::class,
            'watchable_id' => $parent->id,
        ];

        $this->post($this->class_name::indexPath($attributes), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->class_name)->getTable(), [
            'user_id' => $this->user->id,
            'watchable_id' => $parent->id,
        ] + $data);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model_at_a_time()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $parent = $this->createParent();

        $data = [
            'watched_at' => '2020-01-01 00:00:00',
        ];

        $attributes = [
            'watchable_type' => Movie::class,
            'watchable_id' => $parent->id,
        ];

        $this->post($this->class_name::indexPath($attributes), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->class_name)->getTable(), [
            'user_id' => $this->user->id,
            'watchable_id' => $parent->id,
        ] + $data);
    }

    /**
     * @test
     */
    public function a_user_can_get_the_show_json()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $model = $this->createModel([
            'user_id' => $this->user->id,
        ])->load([
            'user',
            'watchable',
        ]);

        $response = $this->getShowJsonResponse($model->route_parameter, $model);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model()
    {
        $this->withoutExceptionHandling();

        $this->signIn($this->user);

        $model = $this->createModel([
            'user_id' => $this->user->id,
        ]);

        $now = now();

        $data = [
            'watched_at' => $now,
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
        $this->withoutExceptionHandling();

        $this->signIn($this->user);

        $model = $this->createModel([
            'user_id' => $this->user->id,
        ]);

        $this->deleteModel($model)
            ->assertRedirect();
    }

    protected function createParent() : Model
    {
        return Movie::factory()->create([
            //
        ]);
    }
}
