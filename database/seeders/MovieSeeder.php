<?php

namespace Database\Seeders;

use App\Models\Movies\Movie;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(database_path('json/movies.json'));
        $data = json_decode($json, true);

        foreach ($data['results'] as $item) {
            $released_at = (new Carbon($item['release_date']));
            Movie::factory()->create([
                'title' => $item['title'],
                'title_en' => $item['title'],
                'overview' => $item['overview'],
                'year' => $released_at->year,
                'released_at' => $released_at,
                'poster_path' => $item['poster_path'],
                'backdrop_path' => $item['backdrop_path'],
            ]);
        }
    }
}
