<?php

namespace Database\Factories\Watched;

use App\Models\Movies\Movie;
use App\Models\User;
use App\Models\Watched\Watched;
use Illuminate\Database\Eloquent\Factories\Factory;

class WatchedFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Watched::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'watchable_type' => Movie::class,
            'watchable_id' => Movie::factory(),
            'watched_at' => $this->faker->dateTime,
        ];
    }
}
