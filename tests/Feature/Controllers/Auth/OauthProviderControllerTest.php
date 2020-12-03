<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\Auth\OauthProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class OauthProviderControllerTest extends TestCase
{
    protected $base_route_name = OauthProvider::ROUTE_NAME;
    protected $base_view_path = OauthProvider::VIEW_PATH;
    protected $class_name = OauthProvider::class;

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
    public function a_user_can_see_the_index_view()
    {
        $this->signIn();

        $this->getIndexViewResponse()
            ->assertViewIs($this->base_view_path . '.index');
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
