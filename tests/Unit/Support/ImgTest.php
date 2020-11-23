<?php

namespace Tests\Unit\Support;

use App\Support\Img;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImgTest extends TestCase
{
    protected $backdrop_path = '';
    protected $poster_path = 'images/5UsK3grJvtQrtzEgqNlDljJW96w.jpg';
    protected $tmdb_poster_url = 'https://image.tmdb.org/t/p/original/8tZYtuWezp8JbcsvHYO0O46tFbo.jpg';

    protected $backdrop;
    protected $poster;


    protected function setUp() : void
    {
        parent::setUp();

        $this->poster = new Img(Storage::path($this->poster_path));
    }

    /**
     * @test
     */
    public function it_knows_its_path()
    {
        $path = $this->poster->getPathname();
        dump($path);
    }

    /**
     * @test
     */
    public function it_calculates_the_new_height()
    {
        $this->assertEquals(1000, $this->poster->getNewHeight(680));
    }

    /**
     * @test
     */
    public function it_can_resize_the_original_image()
    {
        $path = $this->poster->resize(680);
        $this->assertFileExists($path);
        unlink($path);
    }

    /**
     * @test
     */
    public function it_can_be_created_from_url()
    {
        $model = Img::fromUrl($this->tmdb_poster_url);
        dump($model);
    }
}
