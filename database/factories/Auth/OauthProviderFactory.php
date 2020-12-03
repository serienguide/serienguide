<?php

namespace Database\Factories\Auth;

use App\Models\Auth\OauthProvider;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OauthProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OauthProvider::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'provider_type' => 'facebook',
            'provider_id' => $this->faker->numberBetween(1000000, 9999999),
        ];
    }
}
