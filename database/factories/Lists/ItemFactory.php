<?php

namespace Database\Factories\Lists;

use App\Models\Lists\Item;
use App\Models\Lists\Listing;
use App\Models\Movies\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'list_id' => Listing::factory(),
            'medium_type' => Movie::class,
            'medium_id' => Movie::factory(),
            'rank' => $this->faker->randomNumber,
        ];
    }
}
