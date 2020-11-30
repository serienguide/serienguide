<?php

namespace App\Models\Shows\Seasons;

use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;
use App\Traits\Media\HasImages;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Season extends Model
{
    use HasFactory,
        HasImages,
        SoftDeletes;

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
        'tmdb_id',
        'tvdb_id',
        'overview',
        'season_number',
        'episode_count',
        'poster_path',
        'first_aired_at',
        'deleted_at',
    ];

    public function isDeletable() : bool
    {
        return true;
    }

    public function show() : BelongsTo
    {
        return $this->belongsTo(Show::class, 'show_id');
    }

    public function episodes() : HasMany
    {
        return $this->hasMany(Episode::class, 'season_id')->orderBy('episode_number', 'ASC');
    }

    public function updateFromTmdb()
    {
        $attributes = \App\Apis\Tmdb\Shows\Seasons\Season::find($this->show->tmdb_id, $this->season_number);
        if (empty($attributes)) {
            return;
        }
        $this->update($attributes);
        $this->syncFromTmdb($attributes);
    }

    protected function syncFromTmdb($attributes)
    {
        $this->createImageFromTmdb('poster', $attributes['poster_path']);
        $this->syncEpisodesFromTmdb($attributes['episodes']);
    }

    protected function syncEpisodesFromTmdb($tmdb_episodes)
    {
        $this->episodes()->delete();

        foreach ($tmdb_episodes as $tmdb_episode) {
            $episode = $this->episodes()->withTrashed()->updateOrCreate([
                'show_id' => $this->show_id,
                'season_id' => $this->id,
                'episode_number' => $tmdb_episode['episode_number'],
            ], $tmdb_episode + [
                'deleted_at' => null,
            ]);
        }
    }

    public function getBackdropPathAttribute() : string
    {
        return $this->show->backdrop_path;
    }
}