<?php

namespace App\Console\Commands\People\Credits;

use App\Models\People\Credit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteDuplicatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'people:credits:delete_duplicates';

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
        $results = DB::table('credits')
            ->select('id', DB::raw('COUNT(*) AS count'))
            ->groupBy('id')
            ->having('count', '>', 1)
            ->get();

        foreach ($results as $key => $result) {
            $affected_rows = Credit::where('id', $result->id)->take($result->count -1)->delete();
        }

        return 0;
    }
}
