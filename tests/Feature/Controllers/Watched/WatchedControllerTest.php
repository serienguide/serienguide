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
    protected $base_route_name = 'watchable.watched';
    protected $base_view_path = 'watchable.watched';
    protected $class_name = Watched::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = $this->class_name::factory()->create();

        $actions = [
            'index' => ['type' => 'movies', 'watchable' => $model->watchable_id,],
            'create' => ['type' => 'movies', 'watchable' => $model->watchable_id,],
            'store' => ['type' => 'movies', 'watchable' => $model->watchable_id,],
            'show' => ['type' => 'movies', 'watchable' => $model->watchable_id, 'watched' => $model->id],
            'edit' => ['type' => 'movies', 'watchable' => $model->watchable_id, 'watched' => $model->id],
            'update' => ['type' => 'movies', 'watchable' => $model->watchable_id, 'watched' => $model->id],
            'destroy' => ['type' => 'movies', 'watchable' => $model->watchable_id, 'watched' => $model->id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = $this->class_name::factory()->create();

         $this->a_user_can_not_see_models_from_a_different_user([
            'type' => 'movies',
            'watchable' => $modelOfADifferentUser->watchable_id,
            'watched' => $modelOfADifferentUser->id
        ]);
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

        $this->getPaginatedCollection([
            'type' => 'movies',
            'watchable' => $parent->id,
        ]);
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

        $route_parameter = [
            'type' => 'movies',
            'watchable' => $parent->id,
        ];

        $this->post(route($this->base_route_name . '.store', $route_parameter), $data)
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

        $route_parameter = [
            'type' => 'movies',
            'watchable' => $parent->id,
        ];

        $this->post(route($this->base_route_name . '.store', $route_parameter), $data)
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

        $response = $this->getShowJsonResponse([
            'type' => 'movies',
            'watchable' => $model->watchable_id,
            'watched' => $model->id,
        ], $model);
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

        $route_parameter = [
            'type' => 'movies',
            'watchable' => $model->watchable_id,
            'watched' => $model->id
        ];

        $response = $this->put(route($this->base_route_name . '.update', $route_parameter), $data)
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

        $route_parameter = [
            'type' => 'movies',
            'watchable' => $model->watchable_id,
            'watched' => $model->id
        ];

        $this->deleteModel($model, $route_parameter)
            ->assertRedirect();
    }

    protected function createParent() : Model
    {
        return Movie::factory()->create([
            //
        ]);
    }
}
