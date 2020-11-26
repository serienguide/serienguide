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

Route::resource(App\Models\Movies\Movie::ROUTE_NAME, App\Http\Controllers\Movies\MovieController::class);
Route::resource(App\Models\People\Person::ROUTE_NAME, App\Http\Controllers\People\PersonController::class);
Route::resource(App\Models\Shows\Show::ROUTE_NAME, App\Http\Controllers\Shows\ShowController::class);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('{type}/{model}/' . App\Models\Ratings\Rating::ROUTE_NAME, App\Http\Controllers\Ratings\RatingController::class);
    Route::resource('{type}/{model}/' . App\Models\Watched\Watched::ROUTE_NAME, App\Http\Controllers\Watched\WatchedController::class);
    Route::resource(App\Models\Lists\Listing::ROUTE_NAME, App\Http\Controllers\Lists\ListingController::class)->except([
        'index',
        'show',
    ]);
});

Route::resource(App\Models\Lists\Listing::ROUTE_NAME, App\Http\Controllers\Lists\ListingController::class)->only([
    'index',
    'show',
]);

Route::bind('model', function ($id) {
    switch(app()->request->route('type')) {
        case 'movies': return App\Models\Movies\Movie::findOrFail($id); break;
        default: abort(404);
    }
});