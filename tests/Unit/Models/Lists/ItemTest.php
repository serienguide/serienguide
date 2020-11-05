<?php

namespace Tests\Unit\Models\Lists;

use App\Models\Lists\Item;
use App\Models\Lists\Listing;
use App\Models\Movies\Movie;
use Tests\TestCase;

class ItemTest extends TestCase
{
    protected $class_name = Item::class;

    /**
     * @test
     */
    public function it_belongs_to_a_list()
    {
        $parent = Listing::factory()->create();
        $model = $this->class_name::factory()->create([
            'list_id' => $parent->id,
        ]);

        $this->assertEquals($parent->id, $model->list->id);
    }

    /**
     * @test
     */
    public function it_morphs_to_a_medium()
    {
        $movie = Movie::factory()->create();
        $model = $this->class_name::factory()->create([
            'medium_type' => Movie::class,
            'medium_id' => $movie->id,
        ]);

        $this->assertEquals($movie->id, $model->medium->id);
    }
}
