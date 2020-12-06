<?php

namespace App\Support;

use App\Models\Movies\Movie;
use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;


class Media
{
    public static function className(string $media_type) : string
    {
        switch ($media_type) {
            case 'episodes': return Episode::class; break;
            case 'movies': return Movie::class; break;
            case 'shows': return Show::class; break;

            default:
                return '';
                break;
        }
    }
}