<?php

namespace App\Http\Livewire\Media;

use App\Models\Lists\Item;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Listing extends Component
{
    public $model;
    public $lists;

    public function mount($model)
    {
        $this->model = $model;
        $this->lists = $this->getLists();
    }

    public function toggle(int $list_id)
    {
        $attributes = [
            'list_id' => $list_id,
            'medium_type' => get_class($this->model),
            'medium_id' => $this->model->id,
        ];

        $item = Item::where($attributes)->first();
        if ($item) {
            $item->delete();
        }
        else {
            Item::create($attributes);
        }

        $this->lists = $this->getLists();
    }

    protected function getLists()
    {
        return auth()->user()->lists()
            ->with([
                'items' => function ($query) {
                    return $query->where([
                        'medium_type' => get_class($this->model),
                        'medium_id' => $this->model->id,
                    ]);
                },
            ])
            ->get();
    }

    public function render()
    {
        return view('livewire.media.listing');
    }
}
