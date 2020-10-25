<?php

namespace Tests;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $user;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function signIn(User $user = null)
    {
        if (is_null($user))
        {
            $user = $this->user;
        }

        if (! $this->isAuthenticated())
        {
            $this->actingAs($user);
        }

        return $user;
    }

    public function guestsCanNotAccess(array $routes) : void
    {
        $verbs = [
            'index' => 'get',
            'create' => 'get',
            'store' => 'post',
            'show' => 'get',
            'edit' => 'get',
            'update' => 'put',
            'destroy' => 'delete',
        ];

        foreach ($routes as $action => $route) {
            $this->assertAuthenticationRequired($action, $verbs[$action], $route);
        }
    }

    protected function assertAuthenticationRequired(string $action, string $method = 'get', string $route = '') : void
    {
        $this->$method($route)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(basename(route('login')));

        $method .= 'Json';
        $this->$method($route)
            ->assertUnauthorized();
    }

    public function a_user_can_not_see_models_from_a_different_user(Model $modelOfADifferentUser)
    {
        $this->signIn();

        $this->a_different_user_gets_a_403('get', $modelOfADifferentUser->path);

        $this->a_different_user_gets_a_403('get', $modelOfADifferentUser->edit_path);

        $this->a_different_user_gets_a_403('put', $modelOfADifferentUser->path);

        $this->a_different_user_gets_a_403('delete', $modelOfADifferentUser->path);
    }

    protected function a_different_user_gets_a_403(string $method = 'get', string $route) : TestResponse
    {
        $response = $this->$method($route)
            ->assertForbidden();

        return $response;
    }

    public function getIndexViewResponse(array $parameters = []) : TestResponse
    {
        return $this->getViewResponse('index', $parameters);
    }

    public function getCreateViewResponse(array $parameters = []) : TestResponse
    {
        return $this->getViewResponse('create', $parameters);
    }

    public function getShowViewResponse(array $parameters = []) : TestResponse
    {
        return $this->getViewResponse('show', $parameters);
    }

    public function getEditViewResponse(array $parameters = []) : TestResponse
    {
        return $this->getViewResponse('edit', $parameters);
    }

    protected function getViewResponse(string $action, array $parameters = []) : TestResponse
    {
        $response = $this->get(route($this->base_route_name . '.' . $action, $parameters));
        $response->assertStatus(Response::HTTP_OK);

        return $response;
    }

    public function getShowJsonResponse(array $parameters = [], Model $model) : TestResponse
    {
        $response = $this->getJsonResponse('show', $parameters)
            ->assertJson([
                'id' => $model->id,
            ]);

        return $response;
    }

    protected function getJsonResponse(string $action, array $parameters = []) : TestResponse
    {
        $response = $this->getJson(route($this->base_route_name . '.' . $action, $parameters));
        $response->assertStatus(Response::HTTP_OK);

        return $response;
    }

    public function getCollection(string $route, int $assert_json_count = 3) : TestResponse
    {
        $response = $this->getJson($route)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($assert_json_count);

        return $response;
    }

    public function getPaginatedCollection(string $route, int $assert_json_count = 3) : TestResponse
    {
        $response = $this->json('get', $route, []);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'current_page',
                'data',
                'total',
            ])
            ->assertJsonCount($assert_json_count, 'data');

        return $response;
    }

    public function deleteModel(Model $model) : TestResponse
    {
        $table = $model->getTable();

        $this->assertDatabaseHas($table, [
            'id' => $model->id
        ]);

        $this->assertTrue($model->isDeletable());
        $response = $this->delete($model->path)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseMissing($table, [
            'id' => $model->id
        ]);

        return $response;
    }

    protected function createModel(array $attributes = []) : Model
    {
        return $this->class_name::factory()->create($attributes);
    }
}
