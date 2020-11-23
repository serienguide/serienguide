<?php

namespace App\Console\Commands\Apis\Tmdb\Movies;

use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apis:tmdb:movies:update {tmdb_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates a movie from tmdb';

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
        $tmdbMovie = \App\Apis\Tmdb\Movies\Movie::find($this->argument('tmdb_id'));
        dump($tmdbMovie);

        return 0;
    }
}
