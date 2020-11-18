<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $lists = [
            'Merkzettel',
            'Meine Serien',
            'Empfehlungen',
            'Sammlung',
        ];

        foreach ($lists as $key => $name) {
            $user->lists()->create([
                'name' => $name,
            ]);
        }

        $this->call([
            MovieSeeder::class,
        ]);
    }
}
