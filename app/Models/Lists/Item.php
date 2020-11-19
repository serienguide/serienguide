<?php

namespace App\Models\Lists;

use App\Models\Lists\Listing;
use App\Traits\BelongsToMedium;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Item extends Model
{
    use BelongsToMedium, HasFactory;

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
        'list_id',
        'medium_id',
        'medium_type',
        'rank',
    ];

    protected $table = 'list_item';

    /**
     * The booting method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->rank = 0;

            return true;
        });
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function list() : BelongsTo
    {
        return $this->belongsTo(Listing::class, 'list_id');
    }
}