<?php

namespace App\Http\Livewire\Watched;

use Livewire\Component;

class Ul extends Component
{
    public $model;
    public $items;

    public function mount($model)
    {
        $this->items = $model->watched;
    }

    public function destroy(int $index)
    {
        $item = $this->items->splice($index, 1);
        $item->first()->delete();
    }

    public function watch()
    {
        $this->items[-1] = $this->model->watchedBy(auth()->user());
    }


    public function render()
    {
        return view('livewire.watched.ul');
    }
}
