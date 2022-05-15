<?php

namespace App\Console\Commands\Images;

use App\Models\Images\Image;
use Illuminate\Console\Command;

class DeleteWithoutMediumCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:delete-without-medium';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all images and its files without a medium';

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
        $images = Image::whereDoesntHave('medium')->get();
        foreach ($images as $image) {
            $this->line($image->id . ': ' . $image->path);
            $image->delete();
        }
    }
}
