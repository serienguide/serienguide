<?php

namespace App\Http\Controllers\Filters;

use App\Http\Controllers\Controller;
use App\Models\People\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Person::orderBy('tmdb_popularity', 'ASC')
            ->orderBy('name', 'ASC')
            ->search($request->input('query'))
            ->get();
    }
}
