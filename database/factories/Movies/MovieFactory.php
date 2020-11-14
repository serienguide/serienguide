<?php

namespace Database\Factories\Movies;

use App\Models\Movies\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Movie::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'title_en' => $this->faker->sentence(3),
            'year' => $this->faker->numberBetween(1990, 2030),
            'tagline' => $this->faker->sentence,
            'overview' => $this->faker->paragraph,
            'released_at' => $this->faker->date,
            'runtime' => $this->faker->numberBetween(30, 500),
            'homepage' => $this->faker->url,
            'status' => $this->faker->numberBetween(0, 5),
        ];
    }
}
