<?php

namespace Tests\Unit\Apis\Glotz;

use App\Apis\Glotz\Show;
use Tests\TestCase;

class ShowTest extends TestCase
{
    const TVDB_ID_SCRUBS = 76156;

    /**
     * @test
     */
    public function it_can_find_a_show_by_tvdb_id()
    {
        $attributes = Show::find(self::TVDB_ID_SCRUBS);
        dump($attributes);
    }

    /**
     * @test
     */
    public function it_can_get_updated_shows()
    {
        $attributes = Show::updated();
        dump($attributes);
    }
}
