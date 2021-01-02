<?php

namespace App\Http\Livewire\Media;

use Livewire\Component;

class Related extends Component
{
    public $items;
    public $model;

    public function mount($model)
    {
        $this->model = $model;
    }

    public function render()
    {
        return view('livewire.media.related');
    }

    public function loadItems() : void
    {
        $this->items = $this->model->related();
    }
}
