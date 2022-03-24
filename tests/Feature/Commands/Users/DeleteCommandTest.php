<?php

namespace Tests\Feature\Commands\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DeleteCommandTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_delete_a_user()
    {
        $model = User::factory()->create();

        Artisan::call('users:delete', [
            'user' => $model->id
        ]);
    }


}
