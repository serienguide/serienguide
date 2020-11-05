<?php

namespace App\Models\Lists;

use App\Models\Lists\Listing;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Item extends Model
{
    use HasFactory;

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

    protected $table = 'list_item';

    public function isDeletable() : bool
    {
        return true;
    }

    public function list() : BelongsTo
    {
        return $this->belongsTo(Listing::class, 'list_id');
    }

    public function medium() : MorphTo
    {
        return $this->morphTo('medium');
    }
}