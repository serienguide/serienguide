<?php

namespace App\Http\Controllers\Users\Followings;

use App\Http\Controllers\Controller;
use App\Models\Watched\Watched;
use Illuminate\Http\Request;

class LastWatchedController extends Controller
{
    public function index(Request $request)
    {
        $actions = Watched::with([
                'user',
                'watchable',
            ])
            ->whereIn('user_id', auth()->user()->followings->pluck('id'))
            ->latest('watched_at')
            ->paginate(12);

        foreach ($actions as $action) {
            $action->watchable->toCard();
        }

        return $actions;
    }
}
