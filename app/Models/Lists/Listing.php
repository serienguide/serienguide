<?php

namespace App\Models\Lists;

use App\Models\Lists\Item;
use App\Models\User;
use App\Traits\BelongsToUser;
use App\Traits\HasManyComments;
use App\Traits\HasSlug;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Listing extends Model
{
    use BelongsToUser,
        HasFactory,
        HasManyComments,
        HasModelPath,
        HasSlug;

    const ROUTE_NAME = 'users.lists';
    const VIEW_PATH = 'lists';

    const DEFAULT_LISTS = [
        'currently_watching' => 'Meine Serien',
        'recommendations' => 'Empfehlungen',
        'watchlist' => 'Merkzettel',
    ];

    protected $appends = [
        'is_custom',
        'is_watchlist',
        'items_path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'type',
        'user_id',
        'name',
        'description',
    ];

    protected $table = 'lists';

    public function isDeletable() : bool
    {
        return $this->is_custom;
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

    public function getIsCustomAttribute() : bool
    {
        return is_null($this->type);
    }

    public function getIsWatchlistAttribute() : bool
    {
        return ($this->type == 'watchlist');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'user' => $this->user_id,
            'list' => $this->id,
        ];
    }

    public function getItemsPathAttribute() : string
    {
        return route ('lists.items.index', [
            'list' => $this->id,
        ]);
    }

    public function items() : HasMany
    {
        return $this->hasMany(Item::class, 'list_id');
    }

    public static function setup(User $user)
    {
        foreach (self::DEFAULT_LISTS as $type => $name) {
            self::firstOrCreate([
                'user_id' => $user->id,
                'type' => $type,
            ], [
                'name' => $name,
                'default_order' => ($type == 'currently_watching' ? 'name' : 'created_at'),
            ]);
        }
    }
}