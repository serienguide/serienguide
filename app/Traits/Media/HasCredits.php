<?php

namespace App\Traits\Media;

use App\Models\People\Credit;
use App\Models\People\Person;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;

trait HasCredits
{
    public static function bootHasCredits()
    {
        static::creating(function ($model) {
            //
        });
    }

    public function initializeHasCredits()
    {
        //
    }

    public function credits() : MorphToMany
    {
        return $this->morphToMany(Credit::class, 'medium', 'credit_medium');
    }

    public function actors() : MorphToMany
    {
        return $this->credits()->with('person')->where('character', '!=', '')->orderBy('order', 'ASC');
    }

    public function directors() : MorphToMany
    {
        return $this->credits()->with('person')->where('department', 'Directing');
    }

    public function writers() : MorphToMany
    {
        return $this->credits()->with('person')->where('department', 'Writing');
    }

    public function syncCreditsFromTmdb(array $tmdb_credits)
    {
        $credit_ids = [];
        foreach ($tmdb_credits as $type => $types) {
            if (! is_array($types)) {
                continue;
            }
            foreach ($types as $key => $tmdb_credit) {
                $person = Person::firstOrCreate([
                    'id' => $tmdb_credit['id'],
                ], [
                    'name' => $tmdb_credit['name'],
                    'profile_path' => $tmdb_credit['profile_path'],
                    'known_for_department' => $tmdb_credit['known_for_department'],
                    'gender' => $tmdb_credit['gender'],
                ]);
                $credit = Credit::updateOrCreate([
                    'id' => $tmdb_credit['credit_id']
                ], [
                    'person_id' => $person->id,
                    'credit_type' => $type,
                    'department' => Arr::get($tmdb_credit, 'department', ''),
                    'job' => Arr::get($tmdb_credit, 'job', ''),
                    'character' => Arr::get($tmdb_credit, 'character', ''),
                    'order' => Arr::get($tmdb_credit, 'order', 0),
                ]);
                $credit_ids[] = $credit->id;
            }

            $this->credits()->sync($credit_ids);
        }
    }
}