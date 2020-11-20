<?php

namespace Tests\Unit\Movies;

use App\Models\Genres\Genre;
use App\Models\Images\Image;
use App\Models\Lists\Item;
use App\Models\Movies\Movie;
use Tests\TestCase;

class MovieTest extends TestCase
{
    protected $class_name = Movie::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = $this->class_name::factory()->create();
        $route_parameter = [
            'movie' => $model->id,
        ];

        $route = strtok(route($this->class_name::ROUTE_NAME . '.index', $route_parameter), '?');
        $this->assertEquals($route, $this->class_name::indexPath($model->toArray()));

        $route = strtok(route($this->class_name::ROUTE_NAME . '.create', $route_parameter), '?');
        $this->assertEquals($route, $model->create_path);

        $route = route($this->class_name::ROUTE_NAME . '.show', $route_parameter);
        $this->assertEquals($route, $model->path);

        $route = route($this->class_name::ROUTE_NAME . '.edit', $route_parameter);
        $this->assertEquals($route, $model->edit_path);

        $route = strtok(route($this->class_name::ROUTE_NAME . '.index', $route_parameter), '?');
        $this->assertEquals($route, $model->index_path);
    }

    /**
     * @test
     */
    public function it_set_its_slug()
    {
        $model = $this->class_name::factory()->create([
            'title' => 'New Movie',
            'year' => 2020
        ]);

        $this->assertEquals('new-movie-2020', $model->slug);
    }

    /**
     * @test
     */
    public function it_has_many_genres()
    {
        $model = $this->class_name::factory()->create();
        $genres = Genre::factory()->count(3)->create();

        $this->assertCount(0, $model->genres);
        $this->assertCount(0, $genres->first()->movies);

        foreach ($genres as $key => $genre) {
            $model->genres()->attach($genre->id);
        }

        $this->assertCount(3, $model->refresh()->genres);
        $this->assertCount(1, $genres->first()->refresh()->movies);
    }

    /**
     * @test
     */
    public function it_has_many_images()
    {
        $model = $this->class_name::factory()->create();

        $this->assertCount(0, $model->images);

        $images = Image::factory()->count(3)->create([
            'medium_id' => $model->id,
        ]);

        $this->assertCount(3, $model->refresh()->images);
        $this->assertEquals($model->id, $images->first()->refresh()->medium->id);
    }

    /**
     * @test
     */
    public function it_has_many_keywords()
    {
        $model = $this->class_name::factory()->create();
        $keywords = Keyword::factory()->count(3)->create();

        $this->assertCount(0, $model->keywords);
        $this->assertCount(0, $keywords->first()->movies);

        foreach ($keywords as $key => $genre) {
            $model->keywords()->attach($genre->id);
        }

        $this->assertCount(3, $model->refresh()->keywords);
        $this->assertCount(1, $keywords->first()->refresh()->movies);
    }

    /**
     * @test
     */
    public function it_morphs_many_list_items()
    {
        $model = $this->class_name::factory()->create();

        $this->assertCount(0, $model->list_items);

        $list_items = Item::factory()->count(3)->create([
            'medium_type' => Movie::class,
            'medium_id' => $model->id,
        ]);

        $this->assertCount(3, $model->refresh()->list_items);
    }
}
