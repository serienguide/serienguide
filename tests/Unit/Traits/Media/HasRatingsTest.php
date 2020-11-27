<?php

namespace Tests\Unit\Traits\Media;

use App\Models\Movies\Movie;
use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;
use Tests\TestCase;

class HasRatingsTest extends TestCase
{
    protected $class_names = [
        Episode::class,
        Movie::class,
        Show::class,
    ];

    /**
     * @test
     */
    public function it_can_be_rated_by_a_user()
    {
        foreach ($this->class_names as $class_name)
        {
            $model = $class_name::factory()->create();
            $rating = $model->rateBy($this->user, [
                'rating' => 5,
            ]);
            $this->assertCount(1, $model->ratings);
            $this->assertEquals(1, $model->vote_count);
            $this->assertEquals(5, $model->vote_average);

            $rating = $model->rateBy($this->user, [
                'rating' => 7,
            ]);
            $model = $model->refresh();
            $this->assertCount(1, $model->ratings);
            $this->assertEquals(1, $model->vote_count);
            $this->assertEquals(7, $model->vote_average);

            $rating = $model->rateBy($this->user, [
                'rating' => 0,
            ]);
            $model = $model->refresh();
            $this->assertCount(0, $model->ratings);
            $this->assertEquals(0, $model->vote_count);
            $this->assertEquals(0, $model->vote_average);
        }
    }
}
