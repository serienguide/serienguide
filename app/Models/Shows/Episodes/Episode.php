<?php

namespace App\Models\Shows\Episodes;

use App\Models\Shows\Seasons\Season;
use App\Models\Shows\Show;
use App\Traits\Media\HasCredits;
use App\Traits\Media\HasImages;
use App\Traits\Media\HasRatings;
use App\Traits\Media\HasWatched;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Episode extends Model
{
    use HasCredits,
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

    public function getCardImagePathAttribute() : string
    {
        return Storage::disk('s3')->url('w423' . $this->still_path);
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