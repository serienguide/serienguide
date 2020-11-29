<?php

namespace Database\Factories\Shows;

use App\Models\Shows\Show;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShowFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Show::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'name_en' => $this->faker->sentence(3),
            'year' => $this->faker->numberBetween(1990, 2030),
            'tagline' => $this->faker->sentence,
            'overview' => $this->faker->paragraph,
            'first_aired_at' => $this->faker->date,
            'runtime' => $this->faker->numberBetween(30, 500),
            'homepage' => $this->faker->url,
            'status' => $this->faker->numberBetween(0, 5),
            'poster_path' => $this->faker->md5 . '.jpg',
            'backdrop_path' => $this->faker->md5 . '.jpg',
        ];
    }
}
