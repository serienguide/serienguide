<?php

namespace App\Console\Commands\Apis\Tmdb\Trending;

use App\Apis\Tmdb\Trending;
use App\Models\Movies\Movie;
use App\Models\People\Person;
use App\Models\Shows\Show;
use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apis:tmdb:trending:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets trending media and popularity';

    protected $media = [
        'movie' => Movie::class,
        // 'person' => Person::class,
        'tv' => Show::class,
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        foreach($this->media as $type => $class_name) {
            Trending::get($type, $class_name);
        }

        return 0;
    }
}
