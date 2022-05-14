<?php

namespace App\Console\Commands\Images;

use App\Models\Images\Image;
use Illuminate\Console\Command;

class TruncateUnusedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:truncate-unused {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes an image and its files';

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
        $image = Image::findOrFail($this->argument('id'));
        $image->delete();
    }
}
