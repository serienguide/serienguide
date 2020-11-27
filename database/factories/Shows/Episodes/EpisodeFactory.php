<?php

namespace Database\Factories\Shows\Episodes;

use App\Models\Shows\Episodes\Episode;
use Illuminate\Database\Eloquent\Factories\Factory;

class EpisodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Episode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'episode_number' => $this->faker->numberBetween(1, 25),
        ];
    }
}
