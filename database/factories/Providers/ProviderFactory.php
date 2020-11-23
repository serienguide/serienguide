<?php

namespace Database\Factories\Providers;

use App\Models\Providers\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Provider::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'id' => $this->faker->randomNumber,
            'logo_path' => $this->faker->md5 . '.jpg',
        ];
    }
}
