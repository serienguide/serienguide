<?php

namespace App\Models\Images;

use App\Support\Img;
use App\Traits\BelongsToMedium;
use D15r\ModelPath\Traits\HasModelPath;
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

        $paths = [];
        $paths[] = $this->upload(0, $disk);

        foreach (self::WIDTHS[$this->type] as $width) {
            $path = $this->resize($width);
            $paths[] = $this->upload($width, $disk);
        }

        return $paths;
    }

    public function resize(int $width)
    {
        return $this->img->resize($width);
    }

    public function upload(int $width = 0, string $disk = 's3')
    {
        $folder = $width ? 'w' . $width : 'original';
        return Storage::disk($disk)->putFileAs($folder, new File($this->temp_path), basename($this->path));
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getImgAttribute() : Img
    {
        return new Img(Storage::path('images' . $this->path));
    }

    public function getTempPathAttribute() : string
    {
        return Storage::path('images' . $this->path);
    }

    public function getFilenameAttribute() : string
    {
        return basename($this->path);
    }
}