<?php

namespace App\Models\Images;

use App\Traits\BelongsToMedium;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use BelongsToMedium, HasFactory;

    const WIDTHS = [
        'poster' => [ 48, 118, 680 ],
        'backdrop' => [ 75, 423, 750, 1920 ],
        'still' => [ 75, 423, 750, 1920 ],
        'profile' => [],
    ];

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
        'medium_type',
        'medium_id',
        'path',
        'type',
    ];

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
            // Create Images in different sizes on s3

            // in poster_path / backdrop_path in medium speichern

            return true;
        });

        static::deleted(function($model)
        {
            $model->deleteFiles();

            return true;
        });
    }

    public static function createFromTmdb(array $attributes) : self
    {
        // create model
        $model = self::where($attributes)->first();

        if (! is_null($model)) {
            return $model;
        }

        $model = self::create($attributes);
        $model->make_files();

        $model->medium->update([
            $attributes['type'] . '_path' => $model->path,
        ]);

        return $model;
    }

    public function make_files(string $disk = 's3') : array
    {
        if (! $this->path) {
            return [];
        }

        $size = @getimagesize($this->provider_url);
        if ($size === false) {
            return [];
        }

        $original_width = $size[0];
        $original_height = $size[1];
        $type = $size[2];

        switch ($type) {
            case 1: $original = imagecreatefromgif($this->provider_url); break;
            case 2: $original = ImageCreateFromJPEG($this->provider_url); break;
            case 3: $original = imagecreatefrompng($this->provider_url); break;
            default: $original = ImageCreateFromJPEG($this->provider_url); break;
        }

        if ($original === false) {
            return [];
        }

        $paths = [];
        if (! Storage::disk($disk)->exists($this->getDirectory() . $this->path)) {
            ImageJPEG($original, $this->temp_path);
            $paths[] = $this->upload(0, $disk);
            unlink($this->temp_path);
        }

        foreach (self::WIDTHS[$this->type] as $width) {
            if (Storage::disk($disk)->exists($this->getDirectory($width) . $this->path)) {
                continue;
            }

            if ($original_width > $original_height) {
                $height = round($width * 0.5625);
            }
            else {
                $height = round($width / 0.68);
            }

            $neuesBild = imagecreatetruecolor($width, $height);
            ImageCopyResampled($neuesBild, $original, 0, 0, 0, 0, $width, $height, $original_width, $original_height);
            ImageJPEG($neuesBild, $this->temp_path);
            imagedestroy($neuesBild);

            $paths[] = $this->upload($width, $disk);
            unlink($this->temp_path);
        }

        imagedestroy($original);

        return $paths;
    }

    public function deleteFiles(string $disk = 's3')
    {
        $widths = self::WIDTHS[$this->type];
        $widths[] = 0;

        foreach ($widths as $width) {
            Storage::disk($disk)->delete($this->getDirectory($width) . '/' . basename($this->path));
        }
    }

    public function upload(int $width = 0, string $disk = 's3')
    {
        return Storage::disk($disk)->putFileAs($this->getDirectory($width), new File($this->temp_path), basename($this->path));
    }

    public function getDirectory(int $width = 0) : string
    {
        return $width ? 'w' . $width : 'original';
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getTempPathAttribute() : string
    {
        return Storage::path('images' . $this->path);
    }

    public function getProviderUrlAttribute() : string
    {
        return 'https://image.tmdb.org/t/p/original' . $this->path;
    }

    public function getFilenameAttribute() : string
    {
        return basename($this->path);
    }
}