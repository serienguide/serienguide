<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('movies', App\Http\Controllers\Movies\MovieController::class);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('{type}/watchable.watched', App\Http\Controllers\Watched\WatchedController::class);
});

Route::bind('watchable', function ($id) {
    switch(app()->request->route('type')) {
        case 'movies': return App\Models\Movies\Movie::findOrFail($id); break;
        default: abort(404);
    }
});