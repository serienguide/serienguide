<?php

namespace App\Models\Watched;

use App\Models\User;
use App\Traits\BelongsToUser;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class Watched extends Model
{
    use BelongsToUser, HasFactory, HasModelPath;

    const ROUTE_NAME = 'watched';
    const VIEW_PATH = 'watched';

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'watched_at',
    ];

    protected $fillable = [
        'user_id',
        'watchable_id',
        'watchable_type',
        'watched_at',
    ];

    protected $table = 'watched';

    public function isDeletable() : bool
    {
        return true;
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'type' => $this->watchable_type::ROUTE_NAME,
            'model' => $this->watchable_id,
            'watched' => $this->id,
        ];
    }

    public function watchable() : MorphTo
    {
        return $this->morphTo();
    }
}