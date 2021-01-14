<?php

namespace App\Http\Livewire\Comments;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Index extends Component
{
    public $model;
    public $form = [
        'text' => '',
    ];

    public $rules = [
        'form.text' => 'required|string',
    ];

    public function mount(Model $model)
    {
        $this->model = $model;
    }

    public function comment()
    {
        $attributes = $this->validate();

        $this->model->commentByUser(auth()->user(), $this->form);

        $this->form['text'] = '';
    }

    public function getItems()
    {
        return $this->model->comments()->with(['user'])
            ->latest()
            ->paginate();
    }

    public function render()
    {
        return view('livewire.comments.index')
            ->with('items', $this->getItems());
    }
}
