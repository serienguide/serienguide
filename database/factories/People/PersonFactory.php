<?php

namespace Database\Factories\People;

use App\Models\People\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'birthday_at' => $this->faker->date,
            'deathday_at' => $this->faker->date,
            'known_for_department' => $this->faker->randomElement(['Actors']),
            'gender' => $this->faker->numberBetween(1, 2),
            'biography' => $this->faker->paragraph,
            'place_of_birth' => $this->faker->city,
            'homepage' => $this->faker->domainName,
            'profile_path' => $this->faker->md5 . '.jpg',
            'backdrop_path' => $this->faker->md5 . '.jpg',
        ];
    }
}
