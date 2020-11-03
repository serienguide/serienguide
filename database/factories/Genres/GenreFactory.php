<?php

namespace Database\Factories\Genres;

use App\Models\Genres\Genre;
use App\Models\Movies\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class GenreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Genre::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'type' => Movie::class,
            'tmdb_id' => $this->faker->randomNumber,
        ];
    }
}
