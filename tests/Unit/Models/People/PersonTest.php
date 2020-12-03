<?php

namespace Tests\Unit\Models\People;

use App\Models\People\Person;
use Tests\TestCase;

class PersonTest extends TestCase
{
    protected $class_name = Person::class;

    /**
     * @test
     */
    public function it_can_be_updated_from_tmdb()
    {
        // Brad Pitt
        $tmdb_id = 287;
        $model = $this->class_name::factory()->create([
            'id' => $tmdb_id,
        ]);
        $model->updateFromTmdb();
        $this->assertGreaterThan(0, $model->tmdb_popularity);
    }
}
