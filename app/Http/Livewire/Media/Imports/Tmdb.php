<?php

namespace App\Http\Livewire\Media\Imports;

use App\Models\Movies\Movie;
use App\Support\Media;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Tmdb extends Component
{
    public $items = [];
    public $class_name;
    public $media_type;

    public $filter = [
        'search' => '',
    ];

    public function mount(string $media_type)
    {
        $this->media_type = $media_type;
        $this->class_name = Media::className($media_type);
    }

    public function updatedFilterSearch()
    {
        $this->setItems();
    }

    public function import(int $index)
    {
        $item = $this->items[$index];
        $model = $this->class_name::firstOrCreate([
            'tmdb_id' => $item['id'],
        ], [
            'name' => $item['name'],
            'name_en' => $item['name'],
        ]);

        if ($model->wasRecentlyCreated) {
            Artisan::queue('apis:tmdb:' . $this->media_type . ':update', [
                'id' => $model->id,
                '--user' => auth()->user()->id,
            ]);

            return $this->dispatchBrowserEvent('flash', [
                'type' => 'success',
                'text' => $this->class_name::label(1) . ' wurde angelegt und ein Update im Hintergrund angestoßen.',
            ]);
        }
        else {
            return redirect($model->path);
        }

    }

    public function render()
    {
        return view('livewire.media.imports.tmdb');
    }

    protected function setItems() : void
    {
        if (empty($this->filter['search'])) {
            $this->items = [];
            return;
        }

        $method = $this->media_type;
        $this->items = \App\Apis\Tmdb\Search::$method($this->filter['search']);
    }
}
