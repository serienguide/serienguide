<?php

namespace App\Http\Livewire\Watched;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Ul extends Component
{
    public $model;
    public $items;

    protected $listeners = [
        // 'watched' => 'watched',
    ];

    public function mount($model)
    {
        $this->items = $model->watched;
    }

    public function destroy(int $index)
    {
        $items = $this->items->splice($index, 1);
        $item = $items->first();
        $item->delete();

        $this->emitUp('unwatched');
    }

    public function watch()
    {
        $watched = $this->model->watchedBy(auth()->user());
        $this->items[-1] = $watched;
        $this->emitUp('watched', $watched);
    }

    public function watched($watched)
    {
        $this->items = $this->model->load([
            'watched' => function ($query) {
                return $query->where('user_id', auth()->user()->id);
            }
        ]);
    }

    public function render()
    {
        return view('livewire.watched.ul');
    }
}
