<?php

namespace App\View\Components\Users;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

class Notification extends Component
{
    public $notification;
    public $notifiable;
    public $model;
    public $user;
    public $image_type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
        $this->notifiable = $notification->notifiable;
        $this->image_type = $notification->data['image_type'];

        if (Arr::has($notification->data, 'model_type')) {
            $this->model = $notification->data['model_type']::find($notification->data['model_id']);
        }
        if (Arr::has($notification->data, 'user_id')) {
            $this->user = User::find($notification->data['user_id']);
        }

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.users.notification');
    }
}
