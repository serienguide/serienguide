<?php

namespace Tests\Unit\Models\Genres;

use App\Models\Genres\Genre;
use Tests\TestCase;

class GenreTest extends TestCase
{
    protected $class_name = Genre::class;

    /**
     * @test
     */
    public function it_set_its_slug()
    {
        $model = $this->class_name::factory()->create([
            'name' => 'New Model',
        ]);

        $this->assertEquals('new-model', $model->slug);
    }

    /**
     * @test
     */
    public function it_belongs_to_many_movies()
    {

    }
}
