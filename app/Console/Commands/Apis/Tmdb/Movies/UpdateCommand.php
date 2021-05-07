<?php

namespace App\Console\Commands\Apis\Tmdb\Movies;

use App\Models\Movies\Movie;
use App\Models\User;
use App\Notifications\Media\Imported;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apis:tmdb:movies:update {id?} {--user=}';

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
        if (! is_null($this->argument('id'))) {
            $this->update(Movie::findOrFail($this->argument('id')));
            return 0;
        }

        $models = Movie::all();
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

        if ($this->option('user')) {
            $user = User::find($this->option('user'));
            $user->notify(new Imported($model));
        }
    }
}
