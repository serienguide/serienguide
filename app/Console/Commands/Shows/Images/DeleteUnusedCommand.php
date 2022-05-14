<?php

namespace App\Console\Commands\Shows\Images;

use App\Models\Shows\Show;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class DeleteUnusedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shows:images:delete-unused {id?}';

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
            $this->handleMedium(Show::findOrFail($id));
            return 0;
        }

        Show::with('seasons.episodes')->orderBy('id')->chunk(10, function (Collection $media) {
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

        foreach ($medium->seasons as $season) {
            $this->line($season->season_number);
            $medium->deleteUnusedImages('poster');

            foreach ($season->episodes as $episode) {
                $this->line($season->season_number . 'x' . $episode->episode_number);
                $episode->deleteUnusedImages('still');
            }
        }
    }
}
