<?php

namespace App\Models\Ratings;

use App\Traits\BelongsToUser;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Rating extends Model
{
    use BelongsToUser, HasFactory, HasModelPath;

    const ROUTE_NAME = 'ratings';
    const VIEW_PATH = 'ratings';

    protected $appends = [
        'class_name',
        'created_at_formatted',
        'created_at_diff_for_humans',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'user_id',
        'medium_id',
        'medium_type',
        'rating',
    ];

    public function isDeletable() : bool
    {
        return true;
    }

    public function getClassNameAttribute() : string
    {
        return strtolower(class_basename($this));
    }

    public function getCreatedAtFormattedAttribute() : string
    {
        return $this->created_at->format('d.m.Y H:i');
    }

    public function getCreatedAtDiffForHumansAttribute() : string
    {
        return $this->created_at->diffForHumans();
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'type' => $this->medium_type::ROUTE_NAME,
            'model' => $this->medium_id,
            'rating' => $this->id,
        ];
    }

    public function medium() : MorphTo
    {
        return $this->morphTo();
    }

    public function scopeMediumType(Builder $query, $value) : Builder
    {
        if (! $value) {
            return $query;
        }

        return $query->where('medium_type', $value);
    }
}