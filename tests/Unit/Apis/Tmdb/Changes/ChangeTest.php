<?php

namespace Tests\Unit\Apis\Tmdb\Changes;


use App\Apis\Tmdb\Changes\Change;
use Tests\TestCase;

class ChangeTest extends TestCase
{
    const ID_MAD_MAX = 76341;
    const ID_TRIBUTE_VON_PANEM = 70160;

    protected $class_name = Change::class;

    /**
     * @test
     */
    public function it_can_get_the_movie_changes()
    {
        $models = Change::movies();
        $this->assertNotEmpty($models);
        $model = $models->first();
        $this->objectHasAttribute('adult', $model);
        $this->objectHasAttribute('id', $model);
    }

    /**
     * @test
     */
    public function it_can_get_the_show_changes()
    {
        $changes = Change::shows();
        $this->assertNotEmpty($changes);
        $this->arrayHasKey('adult', $changes[0]);
        $this->arrayHasKey('id', $changes[0]);

    }
}
