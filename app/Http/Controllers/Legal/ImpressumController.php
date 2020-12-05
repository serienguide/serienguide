<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImpressumController extends Controller
{
    public function index()
    {
        return view('legal.impressum');
    }
}
