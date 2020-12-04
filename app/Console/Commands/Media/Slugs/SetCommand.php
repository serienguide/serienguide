<?php

namespace App\Console\Commands\Media\Slugs;

use App\Models\Movies\Movie;
use App\Models\Shows\Show;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class SetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:slugs:set {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sets new unique slugs';

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
        switch ($type) {
            case 'movies':
                $class_name = Movie::class;
                break;
            case 'shows':
                $class_name = Show::class;
                break;
            case 'people':
                $class_name = Person::class;
                break;

            default:
                # code...
                break;
        }

        $class_name::orderBy('id')->chunkById(100, function ($models) {
            $this->setSlugs($models);
        });

        return 0;
    }

    protected function setSlugs(Collection $models)
    {
        foreach ($models as $model) {
            $model->setSlug(true)->save();
            $this->line($model->name . ': ' . $model->slug);
        }
    }
}
