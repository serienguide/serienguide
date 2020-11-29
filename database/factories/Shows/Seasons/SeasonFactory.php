<?php

namespace Database\Factories\Shows\Seasons;

use App\Models\Shows\Seasons\Season;
use App\Models\Shows\Show;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeasonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Season::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'season_number' => $this->faker->numberBetween(1, 10),
            'show_id' => Show::factory(),
            'poster_path' => $this->faker->md5 . '.jpg',
        ];
    }
}
