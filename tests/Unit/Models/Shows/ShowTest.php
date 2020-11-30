<?php

namespace Tests\Unit\Models\Shows;

use App\Models\Images\Image;
use App\Models\Keywords\Keyword;
use App\Models\Lists\Item;
use App\Models\Providers\Provider;
use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;
use Tests\TestCase;

class ShowTest extends TestCase
{
    const ID_GAME_OF_THRONES = 1399;
    const ID_PRETTY_LITTLE_LIARS = 31917;

    protected $class_name = Show::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = $this->class_name::factory()->create();
        $route_parameter = [
            'show' => $model->id,
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
            'name' => 'New Model',
            'year' => 2020
        ]);
        $this->assertEquals('new-model-2020', $model->slug);

        $model = $this->class_name::factory()->create([
            'name' => null,
            'year' => 2020
        ]);
        $this->assertNotNull($model->slug);
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

        foreach ($keywords as $key => $keyword) {
            $model->keywords()->attach($keyword->id);
        }

        $this->assertCount(3, $model->refresh()->keywords);
        $this->assertCount(1, $keywords->first()->refresh()->movies);
    }

    /**
     * @test
     */
    public function it_has_many_providers()
    {
        $model = $this->class_name::factory()->create();
        $providers = Provider::factory()->count(3)->create();

        $this->assertCount(0, $model->providers);
        $this->assertCount(0, $providers->first()->movies);

        foreach ($providers as $key => $provider) {
            $model->providers()->attach($provider->id, [
                'display_priority' => ($key + 1),
            ]);
        }

        $this->assertCount(3, $model->refresh()->providers);
        $this->assertCount(1, $providers->first()->refresh()->movies);

        foreach ($model->providers as $key => $provider) {
            $this->assertEquals(($key + 1), $provider->pivot->display_priority);
        }
    }

    /**
     * @test
     */
    public function it_morphs_many_list_items()
    {
        $model = $this->class_name::factory()->create();

        $this->assertCount(0, $model->list_items);

        $list_items = Item::factory()->count(3)->create([
            'medium_type' => Show::class,
            'medium_id' => $model->id,
        ]);

        $this->assertCount(3, $model->refresh()->list_items);
    }

    /**
     * @test
     */
    public function it_can_be_created_from_tmdb()
    {
        $tmdb_id = self::ID_PRETTY_LITTLE_LIARS;
        $model = Show::createOrUpdateFromTmdb($tmdb_id);
        $this->assertGreaterThan(0, $model->genres->count());
        $this->assertGreaterThan(0, $model->keywords->count());
        // $this->assertGreaterThan(0, $model->providers->count());
        $this->assertGreaterThan(0, $model->credits->count());
        $this->assertGreaterThan(0, $model->seasons->count());
        $this->assertCount(2, $model->images);
    }

    /**
     * @test
     */
    public function it_can_be_created_updated_from_tmdb()
    {
        $tmdb_id = self::ID_GAME_OF_THRONES;
        $show = Show::factory()->create([
            'tmdb_id' => $tmdb_id,
        ]);
        $model = Show::createOrUpdateFromTmdb($tmdb_id);
        $this->assertEquals($show->id, $model->id);
        $this->assertCount(4, $model->genres);
        $this->assertCount(6, $model->keywords);
        $this->assertCount(9, $model->providers);
        $this->assertCount(38, $model->credits);
    }

    /**
     * @test
     */
    public function it_can_be_updated_from_tmdb()
    {
        $tmdb_id = self::ID_GAME_OF_THRONES;
        $model = Show::factory()->create([
            'tmdb_id' => $tmdb_id,
        ]);
        $model->updateFromTmdb();
        $this->assertCount(4, $model->genres);
        $this->assertCount(6, $model->keywords);
        $this->assertCount(9, $model->providers);
        $this->assertCount(38, $model->credits);
    }

    /**
     * @test
     */
    public function it_gets_the_absolute_numbers_for_episodes()
    {
        $episode = Episode::factory()->create();
        $show = $episode->show;
        $absolute_numbers = $show->setAbsoluteNumbers();
        $this->assertEquals(1, $episode->refresh()->absolute_number);
    }
}
