<?php

namespace Tests\Unit\Traits\Media;

use App\Models\Images\Image;
use App\Models\Movies\Movie;
use App\Models\People\Person;
use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Seasons\Season;
use App\Models\Shows\Show;
use Tests\TestCase;

class HasImagesTest extends TestCase
{
    protected $class_names = [
        Episode::class,
        Movie::class,
        Person::class,
        Season::class,
        Show::class,
    ];

    /**
     * @test
     */
    public function it_has_image_paths_ans_urls()
    {
        foreach ($this->class_names as $class_name)
        {
            $model = $class_name::factory()->create();
            $this->assertNotNull($model->poster_path);
            $this->assertNotNull($model->poster_url_original);
            $this->assertNotNull($model->poster_url);
            $this->assertNotNull($model->poster_url_xs);
            $this->assertNotNull($model->backdrop_path);
            $this->assertNotNull($model->backdrop_url);
        }
    }
}
