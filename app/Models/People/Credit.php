<?php

namespace App\Models\People;

use App\Models\People\Person;
use App\Traits\BelongsToMedium;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Credit extends Model
{
    use BelongsToMedium, HasFactory, HasModelPath;

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
        'id',
        'person_id',
        'medium_type',
        'medium_id',
        'credit_type',
        'department',
        'job',
    ];

    public $incrementing = false;

    public function isDeletable() : bool
    {
        return true;
    }

    public function getCharacterWithPersonNameAttribute()
    {
        return $this->character . ' (' . $this->person->name . ')';
    }

    public function person() : BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}