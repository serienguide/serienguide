<?php

namespace App\Console\Commands\Movies\Images;

use App\Models\Movies\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class DeleteUnusedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:images:delete-unused {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all images that are not used anymore';

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
        foreach ($this->getMedia() as $key => $medium) {
            $this->line($medium->name);

            $medium->deleteUnusedImages('backdrop');
            $medium->deleteUnusedImages('poster');
        }

        return 0;
    }

    protected function getMedia(): Collection
    {
        $id = $this->argument('id');
        if ($id) {
            return collect([
                Movie::findOrFail($id)
            ]);
        }

        return Movie::all();
    }
}
