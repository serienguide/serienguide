<?php

namespace App\Http\Controllers\Users\Profiles;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request, User $user, ?string $section = 'index')
    {
        $user->load([
            'last_watched.watchable',
        ]);

        return view('users.profiles.show')
            ->with('section', $section)
            ->with('user', $user);
    }
}
