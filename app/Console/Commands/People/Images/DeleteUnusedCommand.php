<?php

namespace App\Console\Commands\People\Images;

use App\Models\People\Person;
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
    protected $signature = 'people:images:delete-unused {id?}';

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
            $this->handleMedium(Person::findOrFail($id));
            return 0;
        }

        Person::orderBy('id')->chunk(100, function (Collection $media) {
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
        $medium->deleteUnusedImages('profile');
    }
}
