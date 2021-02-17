<?php

namespace App\Console\Commands\Users;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class SetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:setup {--user=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets up all data for a user';

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
        foreach ($this->getUsers() as $key => $user) {
            $this->line('Setting up User: ' . $user->name . ' (' . $user->id . ')');
            $user->setup();
        }

        return 0;
    }

    protected function getUsers() : Collection
    {
        if ($this->option('user')) {
            return User::where('id', $this->option('user'))->get();
        }

        return User::get();
    }
}
