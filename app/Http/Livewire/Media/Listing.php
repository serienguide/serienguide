<?php

namespace App\Http\Livewire\Media;

use App\Models\Lists\Item;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Listing extends Component
{
    public $model;
    public $lists;
    public $is_custom = false;

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
            $this->emitUp('list-item-destroyed');
        }
        else {
            Item::create($attributes);
            $this->emitUp('list-item-created');
        }

        $this->lists = $this->getLists();
    }

    public function addList(string $name)
    {
        $list = auth()->user->lists()::create([
            'name' => $name,
        ]);

        $this->toggle($list->id);
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
            ->orderBy(DB::raw('IF(lists.type IS NULL, 0, 1)'), 'DESC')
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function render()
    {
        return view('livewire.media.listing');
    }
}
