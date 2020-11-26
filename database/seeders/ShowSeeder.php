<?php

namespace Database\Seeders;

use App\Models\Shows\Show;
use Illuminate\Database\Seeder;
use Illuminate\Support\Artisan;

class ShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(database_path('json/shows.json'));
        $data = json_decode($json, true);

        foreach ($data['results'] as $item) {
            $show = Show::create([
                'tmdb_id' => $item['id'],
            ]);
        }
    }
}
