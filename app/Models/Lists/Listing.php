<?php

namespace App\Models\Lists;

use App\Models\Lists\Item;
use App\Traits\BelongsToUser;
use App\Traits\HasSlug;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Listing extends Model
{
    use BelongsToUser, HasFactory, HasModelPath, HasSlug;

    const ROUTE_NAME = 'users.lists';
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
        'description',
    ];

    protected $table = 'lists';

    public function isDeletable() : bool
    {
        return true;
    }

    public function setSlug() : void
    {
        if ($this->id && ! $this->isDirty('name')) {
            return;
        }

        $slug = Str::slug($this->name, '-', 'de');
        if (self::where('id', '!=', $this->id)->where('user_id', $this->user_id)->slug($slug)->exists()) {
            $slug .= '-' . (self::where('user_id', $this->user_id)->where('name', $this->name)->count());
        }
        $this->attributes['slug'] = $slug;
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'user' => $this->user_id,
            'list' => $this->id,
        ];
    }

    public function items() : HasMany
    {
        return $this->hasMany(Item::class, 'list_id');
    }
}