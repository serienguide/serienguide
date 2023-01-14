<?php

namespace App\Console\Commands\Stats;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class WatchedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:watched';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watched Episodes and movies grouped by user';

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
        $sql = "SELECT
                    user_id,
                    SUM(IF(show_id = 0, 1, 0)) AS movies_count,
                    SUM(IF(show_id = 0, 0, 1)) AS episodes_count
                FROM
                    watched
                WHERE
                    watched_at >= DATE(NOW() - INTERVAL 7 DAY)
                GROUP BY
                    user_id";
        $results = DB::select($sql);

        $users = [];
        $episodes_count = 0;
        $movies_count = 0;
        foreach ($results as $key => $result) {
            $user = User::find($result->user_id);
            $users[] = [
                $key + 1,
                $result->user_id,
                $user->name,
                $result->movies_count,
                $result->episodes_count,
            ];
            $episodes_count += $result->episodes_count;
            $movies_count += $result->movies_count;
        }

        $users[] = [
            '',
            '',
            'Total',
            $movies_count,
            $episodes_count,
        ];

        $this->line('Watched Episodes and movies grouped by user for the last 7 days');
        $this->table(['#', 'user_id', 'username', 'movies_count', 'episodes_count'], $users);
    }
}
