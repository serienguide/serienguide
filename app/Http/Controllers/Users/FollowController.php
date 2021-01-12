<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        auth()->user()->follow($user);

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Du folgst jetzt ' . $user->name . '.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        auth()->user()->unfollow($user);

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Du folgst ' . $user->name . ' nicht mehr.',
            ]);
    }
}
