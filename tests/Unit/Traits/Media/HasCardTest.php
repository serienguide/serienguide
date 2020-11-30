<?php

namespace Tests\Unit\Traits\Media;

use App\Models\Movies\Movie;
use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;
use Tests\TestCase;

class HasCardTest extends TestCase
{
    protected $class_names = [
        Episode::class,
        Movie::class,
        Show::class,
    ];

    /**
     * @test
     */
    public function it_has_ist_class_name()
    {
        $class_names = [
            'episode',
            'movie',
            'show',
        ];

        foreach ($this->class_names as $key => $class_name)
        {
            $model = $class_name::factory()->create();
            $this->assertEquals($class_names[$key], $model->class_name);
        }
    }

    /**
     * @test
     */
    public function it_knows_if_it_is_a_class()
    {
        foreach ($this->class_names as $key => $class_name)
        {
            $model = $class_name::factory()->create();
            $this->assertTrue($model->isClass($class_name));
        }
    }

    /**
     * @test
     */
    public function it_knows_if_it_is_an_episode()
    {
        $model = Episode::factory()->create();
        $this->assertTrue($model->is_episode);
        $this->assertFalse($model->is_movie);
        $this->assertFalse($model->is_show);

        $model = Movie::factory()->create();
        $this->assertFalse($model->is_episode);
        $this->assertTrue($model->is_movie);
        $this->assertFalse($model->is_show);

        $model = Show::factory()->create();
        $this->assertFalse($model->is_episode);
        $this->assertFalse($model->is_movie);
        $this->assertTrue($model->is_show);
    }

}
