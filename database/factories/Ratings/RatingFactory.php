<?php

namespace Database\Factories\Ratings;

use App\Models\Movies\Movie;
use App\Models\Ratings\Rating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'medium_type' => Movie::class,
            'medium_id' => Movie::factory(),
            'rating' => $this->faker->numberBetween(1, 10),
        ];
    }
}
