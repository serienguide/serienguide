<?php

namespace Tests\Unit\Models\People;

use App\Models\People\Credit;
use Tests\TestCase;

class CreditTest extends TestCase
{
    protected $class_name = Credit::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = $this->class_name::factory()->create();
        dump($model);
    }
}
