<?php

namespace Database\Factories\Images;

use App\Models\Images\Image;
use App\Models\Movies\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'medium_type' => Movie::class,
            'medium_id' => Movie::factory(),
            'path' => $this->faker->md5 . '.jpg',
            'type' => $this->faker->randomElement(['poster', 'backdrop']),
        ];
    }
}
