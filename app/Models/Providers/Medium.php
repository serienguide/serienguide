<?php

namespace App\Models\Providers;

use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medium extends Model
{
    use HasFactory, HasModelPath;

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
        //
    ];

    public function isDeletable() : bool
    {
        return true;
    }
}