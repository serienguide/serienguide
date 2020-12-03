<?php

namespace Database\Factories\Auth;

use App\Models\Auth\OauthProvider;
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
            //
        ];
    }
}
