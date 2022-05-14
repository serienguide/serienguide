<?php

namespace App\Console\Commands\Movies\Collections\Images;

use App\Models\Movies\Collection as MoviesCollection;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class DeleteUnusedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:collections:images:delete-unused {id?}';

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
        $id = $this->argument('id');
        if ($id) {
            $this->handleMedium(MoviesCollection::findOrFail($id));
            return 0;
        }

        MoviesCollection::orderBy('id')->chunk(100, function (Collection $media) {
            foreach ($media as $medium) {
                $this->handleMedium($medium);
            }
        });

        return 0;
    }

    /**
     * Deletes all images that are not used anymore
     *
     * @param Model $medium
     */
    protected function handleMedium(Model $medium)
    {
        $this->line($medium->name);

        $medium->deleteUnusedImages('backdrop');
        $medium->deleteUnusedImages('poster');
    }
}
