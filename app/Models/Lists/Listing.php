<?php

namespace App\Models\Lists;

use App\Models\Lists\Item;
use App\Traits\BelongsToUser;
use App\Traits\HasSlug;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listing extends Model
{
    use BelongsToUser, HasFactory, HasModelPath, HasSlug;

    const ROUTE_NAME = 'lists';
    const VIEW_PATH = 'lists';

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
        'name',
    ];

    protected $table = 'lists';

    public function isDeletable() : bool
    {
        return true;
    }

    public function items() : HasMany
    {
        return $this->hasMany(Item::class, 'list_id');
    }
}