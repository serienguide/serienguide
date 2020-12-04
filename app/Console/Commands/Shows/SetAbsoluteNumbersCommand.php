<?php

namespace App\Console\Commands\Shows;

use Illuminate\Console\Command;

class SetAbsoluteNumbersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shows:set_absolute_numbers {id=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $models = Show::whereHas('episodes', function ($query) {
            $query->where('absolute_number', 0);
        })->get();
        foreach ($models as $key => $model) {
            $this->update($model);
        }

        $this->info('');
        $this->info('Completed');

        return 0;
    }

    public function update(Show $show)
    {
        $show->setAbsoluteNumbers();
    }
}
