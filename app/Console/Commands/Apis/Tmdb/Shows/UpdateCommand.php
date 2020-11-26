<?php

namespace App\Console\Commands\Apis\Tmdb\Shows;

use App\Models\Shows\Show;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apis:tmdb:shows:update {id=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates a show from tmdb';

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
            $this->update(Show::findOrFail($this->argument('id')));
            return 0;
        }

        $models = Show::all();
        foreach ($models as $key => $model) {
            $this->update($model);
        }

        $this->info('');
        $this->info('Completed');

        return 0;
    }

    protected function update(Model $model)
    {
        $this->info('Updating ' . $model->name ?? $model->tmdb_id);
        $model->updateFromTmdb($model->tmdb_id);
        foreach ($model->seasons as $season) {
            $season->updateFromTmdb();
        }
    }
}
