<?php

namespace Tests\Feature\Controllers\Watched;

use App\Models\Movies\Movie;
use App\Models\Watched\Watched;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class WatchedControllerTest extends TestCase
{
    protected $base_route_name = 'movies.watched';
    protected $base_view_path = 'movies.watched';
    protected $className = Watched::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = $this->className::factory()->create();

        $actions = [
            'index' => ['movie' => $model->watchable_id],
            'create' => ['movie' => $model->watchable_id],
            'store' => ['movie' => $model->watchable_id],
            'show' => ['movie' => $model->watchable_id, 'watched' => $model->id],
            'edit' => ['movie' => $model->watchable_id, 'watched' => $model->id],
            'update' => ['movie' => $model->watchable_id, 'watched' => $model->id],
            'destroy' => ['movie' => $model->watchable_id, 'watched' => $model->id],
        ];
        $this->guestsCanNotAccess($actions);
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
            'movie' => $parent->id
        ];

        $this->post(route($this->base_route_name . '.store', $route_parameter), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->className)->getTable(), [
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

        $model = Watched::factory()->create()->load([
            'user',
            'watchable',
        ]);

        $response = $this->getShowJsonResponse([
            'movie' => $model->watchable_id,
            'watched' => $model->id,
        ]);
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
            'movie' => $model->watchable_id,
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
            'movie' => $model->watchable_id,
            'watched' => $model->id
        ];

        $this->deleteModel($model, $route_parameter)
            ->assertRedirect();
    }
}
