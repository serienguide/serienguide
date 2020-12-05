<?php

namespace App\Console\Commands\Apis\Tmdb\Episodes;

use App\Models\Shows\Episodes\Episode;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apis:tmdb:episodes:update {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates an episode from tmdb';

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
        if ($this->argument('id')) {
            $this->update(Episode::findOrFail($this->argument('id')));
            return 0;
        }

        $models = Episode::all();
        foreach ($models as $key => $model) {
            $this->update($model);
        }

        $this->info('');
        $this->info('Completed');

        return 0;
    }

    protected function update(Model $model)
    {
        $this->info('Updating ' . $model->name);
        $model->updateFromTmdb();
    }
}
