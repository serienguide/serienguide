<?php

namespace Database\Factories\Keywords;

use App\Models\Keywords\Keyword;
use App\Models\Movies\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class KeywordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Keyword::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'tmdb_id' => $this->faker->randomNumber,
        ];
    }
}
