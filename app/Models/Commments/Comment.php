<?php

namespace App\Models\Commments;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use BelongsToUser,
        HasFactory;

    const ROUTE_NAME = '';
    const VIEW_PATH = '';

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'user_id',
        'medium_type',
        'medium_id',
        'text',
    ];

    public function isDeletable() : bool
    {
        return true;
    }
}