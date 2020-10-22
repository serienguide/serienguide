<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

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

    public function getModel(array $parameters = [], Model $model) : TestResponse
    {
        $response = $this->getJson(route($this->base_route_name . '.show', $parameters))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'id' => $model->id,
            ]);

        return $response;
    }

    public function getCollection(array $parameters = [], int $assert_json_count = 3) : TestResponse
    {
        $response = $this->getJson(route($this->base_route_name . '.index', $parameters))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($assert_json_count);

        return $response;
    }

    public function getPaginatedCollection(array $parameters = [], int $assert_json_count = 3) : TestResponse
    {
        $response = $this->json('get', route($this->base_route_name . '.index', $parameters), []);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'current_page',
                'data',
                'total',
            ])
            ->assertJsonCount($assert_json_count, 'data');

        return $response;
    }

    public function deleteModel(Model $model, array $parameters) : TestResponse
    {
        $table = $model->getTable();

        $this->assertDatabaseHas($table, [
            'id' => $model->id
        ]);

        $this->assertTrue($model->isDeletable());
        $response = $this->delete(route($this->base_route_name . '.destroy', $parameters))
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseMissing($table, [
            'id' => $model->id
        ]);

        return $response;
    }
}
