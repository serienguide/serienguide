<?php

namespace Tests\Unit\Models\Images;

use App\Models\Images\Image;
use App\Models\Movies\Movie;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageTest extends TestCase
{
    protected $class_name = Image::class;

    /**
     * @test
     */
    public function it_can_resize_the_original_image()
    {
        $model = $this->create_model_with_image();
        $path = $model->resize(680);
        $this->assertFileExists($path);
        unlink($path);
    }

    /**
     * @test
     */
    public function it_can_upload_the_file()
    {
        $disk = 'local';
        $model = $this->create_model_with_image();
        $path = $model->upload(0, $disk);
        $this->assertFileExists(Storage::path($path));
        Storage::delete($path);
    }

    /**
     * @test
     */
    public function it_creates_files_and_uploads_them()
    {
        $model = $this->create_model_with_image();
        $paths = $model->make_files('local');
        foreach ($paths as $key => $path) {
            $this->assertFileExists(Storage::path($path));
            Storage::delete($path);
            Storage::deleteDirectory(dirname($path));
        }
    }

    /**
     * @test
     */
    public function it_can_be_created_from_tmdb()
    {
        $movie = Movie::factory()->create([

        ]);

        $poster_path = '/5UsK3grJvtQrtzEgqNlDljJW96w.jpg';
        $model = Image::createFromTmdb([
            'type' => 'poster',
            'path' => $poster_path,
            'medium_type' => Movie::class,
            'medium_id' => $movie->id,
        ]);

        $this->assertEquals($poster_path, $model->path);
        $this->assertTrue(Storage::disk('s3')->exists('original' . $poster_path));
        $this->assertEquals($poster_path, $movie->refresh()->poster_path);
    }

    protected function create_model_with_image() : Image
    {
        return $this->class_name::factory()->create([
            'path' => '/5UsK3grJvtQrtzEgqNlDljJW96w.jpg',
        ]);
    }

}
