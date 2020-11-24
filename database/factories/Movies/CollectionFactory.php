<?php

namespace Database\Factories\Movies;

use App\Models\Movies\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Collection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->randomNumber,
            'name' => $this->faker->sentence(3),
            'poster_path' => $this->faker->md5 . '.jpg',
            'backdrop_path' => $this->faker->md5 . '.jpg',
        ];
    }
}
