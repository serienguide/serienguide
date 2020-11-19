<?php

namespace Database\Factories\People;

use App\Models\Movies\Movie;
use App\Models\People\Credit;
use App\Models\People\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Credit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->md5,
            'person_id' => Person::factory(),
            'medium_type' => Movie::class,
            'medium_id' => Movie::factory(),
            'credit_type' => $this->faker->randomElement(['cast']),
            'department' => $this->faker->randomElement(['Actors']),
            'job' => $this->faker->randomElement(['Actor']),
        ];
    }
}
