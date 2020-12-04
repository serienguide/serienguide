<?php

namespace Tests\Unit\Traits;

use App\Models\Movies\Movie;
use App\Models\People\Person;
use App\Models\Shows\Show;
use Tests\TestCase;

class HasSlugTest extends TestCase
{
    /**
     * @test
     */
    public function it_generates_a_unique_slug_for_a_movie()
    {
        $model = Movie::factory()->create([
            'name' => null,
            'year' => null,
        ]);
        $this->assertNotNull($model->slug);

        $model = Movie::factory()->create([
            'name' => 'New Model',
            'year' => null,
        ]);
        $this->assertEquals('new-model', $model->slug);

        $model = Movie::factory()->create([
            'name' => 'New Model',
            'year' => '2020',
        ]);
        $this->assertEquals('new-model-2020', $model->slug);

        $model = Movie::factory()->create([
            'name' => 'New Model',
            'year' => '2020',
        ]);
        $this->assertEquals('new-model-2020-2', $model->slug);

        $model->update([
            'name' => 'New Model',
            'year' => null,
        ]);
        $this->assertEquals('new-model-2', $model->slug);
    }

    /**
     * @test
     */
    public function it_generates_a_unique_slug_for_a_show()
    {
        $model = Show::factory()->create([
            'name' => null,
            'year' => null,
        ]);
        $this->assertNotNull($model->slug);

        $model = Show::factory()->create([
            'name' => 'New Model',
            'year' => null,
        ]);
        $this->assertEquals('new-model', $model->slug);

        $model = Show::factory()->create([
            'name' => 'New Model',
            'year' => '2020',
        ]);
        $this->assertEquals('new-model-2020', $model->slug);

        $model = Show::factory()->create([
            'name' => 'New Model',
            'year' => '2020',
        ]);
        $this->assertEquals('new-model-2020-2', $model->slug);

        $model->update([
            'name' => 'New Model',
            'year' => null,
        ]);
        $this->assertEquals('new-model-2', $model->slug);
    }

    /**
     * @test
     */
    public function it_generates_a_unique_slug_for_a_person()
    {
        $model = Person::factory()->create([
            'name' => null,
        ]);
        $this->assertNotNull($model->slug);

        $model = Person::factory()->create([
            'name' => 'New Model',
        ]);
        $this->assertEquals('new-model', $model->slug);

        $model = Person::factory()->create([
            'name' => 'New Model',
        ]);
        $this->assertEquals('new-model-2', $model->slug);

        $model->update([
            'name' => 'New Model',
        ]);
        $this->assertEquals('new-model-2', $model->slug);
    }
}
