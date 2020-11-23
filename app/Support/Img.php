<?php

namespace App\Support;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class Img extends File
{
    public $imageinfo;

    public $resource;

    public static function fromUrl(string $url) : self
    {
        $path = Storage::path('images/' . basename($url));
        copy($url, $path);

        return new self($path);
    }

    public function __construct(string $path, bool $checkPath = true)
    {
        parent::__construct($path, $checkPath);

        $this->imageinfo = getimagesize($this->getPathname());
        $this->setResource();
    }

    protected function setResource()
    {
        switch ($this->imageinfo[2]) {
            case IMG_GIF: return $this->resource = imagecreatefromgif($this->getPathname()); break;
            case IMG_JPG: return $this->resource = ImageCreateFromJPEG($this->getPathname()); break;
            case IMG_PNG: return $this->resource = imagecreatefrompng($this->getPathname()); break;
            default: return $this->resource = ImageCreateFromJPEG($this->getPathname()); break;
        }
    }

    public function getNewHeight(int $width) : int
    {
        if ($this->imageinfo[0] > $this->imageinfo[1]) {
            return round($width * 0.5625);
        }

        return round($width / 0.68);
    }

    public function resize(int $width)
    {
        $path = Storage::path('images/temp/' . $this->getFilename());
        $height = $this->getNewHeight($width);
        $image = imagecreatetruecolor($width, $height);
        ImageCopyResampled($image, $this->resource, 0, 0, 0, 0, $width, $height, $this->imageinfo[0], $this->imageinfo[1]);
        ImageJPEG($image, $path);
        imagedestroy($image);

        return $path;
    }


}

?>