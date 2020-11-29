<?php

namespace App\Models\Shows\Episodes;

use App\Models\Shows\Seasons\Season;
use App\Models\Shows\Show;
use App\Models\User;
use App\Models\Watched\Watched;
use App\Traits\Media\HasCard;
use App\Traits\Media\HasCredits;
use App\Traits\Media\HasImages;
use App\Traits\Media\HasRatings;
use App\Traits\Media\HasWatched;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Episode extends Model
{
    use HasCard,
        HasCredits,
        HasFactory,
        HasImages,
        HasRatings,
        // HasModelPath,
        HasWatched,
        SoftDeletes;

    const ROUTE_NAME = '';
    const VIEW_PATH = '';

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'first_aired_at',
    ];

    protected $fillable = [
        'deleted_at',
        'episode_number',
        'first_aired_at',
        'name',
        'name_en',
        'overview',
        'production_code',
        'season_id',
        'show_id',
        'still_path',
        'tmdb_id',
        'tvdb_id',
        'vote_count',
        'vote_average',
        'tmdb_vote_count',
        'tmdb_vote_average',
    ];

    public function isDeletable() : bool
    {
        return true;
    }

    public function updateFromTmdb($tmdb_model = null)
    {
        if (is_null($tmdb_model)) {
            $tmdb_model = \App\Apis\Tmdb\Shows\Episodes\Episode::find($this->show->tmdb_id, $this->season->season_number, $this->episode_number);
        }
        $this->update($tmdb_model->toArray());
        $this->syncFromTmdb($tmdb_model);
    }

    protected function syncFromTmdb($tmdb_model)
    {
        $this->createImageFromTmdb('still', $tmdb_model->still_path);
        $this->syncCreditsFromTmdb([
            'crew' => $tmdb_model->crew,
            'guest_stars' => $tmdb_model->guest_stars,
        ]);
    }

    public function getBackdropPathAttribute() : string
    {
        return $this->still_path ?: $this->show->backdrop_path;
    }

    public function getCardImagePathAttribute() : string
    {
        return Storage::disk('s3')->url($this->backdrop_path ? 'w423' . $this->backdrop_path : 'no/750x422.png');
    }

    public function watchedBy(User $user, array $attributes = []) : Watched
    {
        return $this->watched()->create([
            'user_id' => $user->id,
            'watched_at' => Arr::get($attributes, 'watched_at', now()),
            'show_id' => $this->show_id,
        ]);
    }

    public function season() : BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function show() : BelongsTo
    {
        return $this->belongsTo(Show::class, 'show_id');
    }
}