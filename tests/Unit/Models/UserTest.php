<?php

namespace Tests\Unit\Models;

use App\Models\Lists\Listing;
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

    /**
     * @test
     */
    public function it_creates_all_data_after_been_created()
    {
        $this->assertNotNull($this->user->watchlist);
        $this->assertNotNull($this->user->currently_watching_list);
        $this->assertCount(1, $this->user->lists()->where('type', 'recommendations')->get());
    }

    /**
     * @test
     */
    public function it_creates_all_data_after_been_created_just_once()
    {
        foreach (Listing::DEFAULT_LISTS as $type => $name) {
            $this->assertCount(1, $this->user->lists()->where('type', $type)->get());
        }

        $this->user->setup();

        foreach (Listing::DEFAULT_LISTS as $type => $name) {
            $this->assertCount(1, $this->user->lists()->where('type', $type)->get());
        }
    }

    /**
     * @test
     */
    public function it_can_truncate_all_data()
    {
        $model = $this->class_name::factory()->create([
            'name' => 'New Model',
        ]);
        $model->truncate();
    }
}
