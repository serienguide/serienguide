<?php

namespace Tests\Feature\Comamnds\Apis\Tmdb\Movies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UpdateCommandTest extends TestCase
{
    const JASON_BOURNE_TMDB_ID = 324668;

    /**
     * @test
     */
    public function it_can_update_a_movie()
    {
        Artisan::call('apis:tmdb:movies:update', [
            'tmdb_id' => self::JASON_BOURNE_TMDB_ID
        ]);
    }


}
