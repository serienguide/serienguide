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

Route::get('login/{provider}', [App\Http\Controllers\Auth\ProviderController::class, 'redirectToProvider'])->name('login.provider.redirect');
Route::get('login/{provider}/callback', [App\Http\Controllers\Auth\ProviderController::class, 'handleProviderCallback'])->name('login.provider.callback');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/shows/{show}/update/tmdb', [ App\Http\Controllers\Shows\Updates\TmdbController::class, 'show' ])->name('shows.update.tmdb');
Route::get('/seasons/{season}/update/tmdb', [ App\Http\Controllers\Shows\Seasons\Updates\TmdbController::class, 'show' ])->name('seasons.update.tmdb');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('{type}/{model}/' . App\Models\Ratings\Rating::ROUTE_NAME, App\Http\Controllers\Ratings\RatingController::class);
    Route::resource('{type}/{model}/' . App\Models\Watched\Watched::ROUTE_NAME, App\Http\Controllers\Watched\WatchedController::class);
    Route::resource(App\Models\Lists\Listing::ROUTE_NAME, App\Http\Controllers\Lists\ListingController::class)->except([
        'index',
        'show',
    ]);
    Route::resource('/auth/' . App\Models\Auth\OauthProvider::ROUTE_NAME, App\Http\Controllers\Auth\OauthProviderController::class);

    Route::get('/apis/trakt/watched_history/{provider}', [ App\Http\Controllers\Apis\Trakt\WatchedHistoryController::class, 'show' ])->name('apis.trakt.watched_history.show');

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