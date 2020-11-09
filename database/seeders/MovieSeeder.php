<?php

namespace Database\Seeders;

use App\Models\Movies\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Movie::factory()->count(1)->create();
    }
}
