<?php

namespace App\Models\Watched;

use App\Models\User;
use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Watched extends Model
{
    use BelongsToUser, HasFactory;

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

    public function watchable() : MorphTo
    {
        return $this->morphTo();
    }
}