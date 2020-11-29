<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected $class_name = User::class;

    /**
     * @test
     */
    public function it_creates_a_unique_slug()
    {
        $model = $this->class_name::factory()->create([
            'name' => 'New Model',
        ]);
        $this->assertEquals('new-model', $model->slug);

        $model = $this->class_name::factory()->create([
            'name' => 'New Model',
        ]);
        $this->assertEquals('new-model-1', $model->slug);
        $model_1 = $model;

        $model = $this->class_name::factory()->create([
            'name' => 'New Model',
        ]);
        $this->assertEquals('new-model-2', $model->slug);

        $model_1->update([
            'name' => 'New Model',
            'last_login_at' => now(),
        ]);
        $this->assertEquals('new-model-1', $model_1->refresh()->slug);
    }
}
