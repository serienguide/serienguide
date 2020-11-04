<?php

namespace Tests\Unit\Models\Keywords;

use App\Models\Keywords\Keyword;
use Tests\TestCase;

class KeywordTest extends TestCase
{
    protected $class_name = Keyword::class;

    /**
     * @test
     */
    public function it_set_its_slug()
    {
        $model = $this->class_name::factory()->create([
            'name' => 'New Model',
        ]);

        $this->assertEquals('new-model', $model->slug);
    }
}
