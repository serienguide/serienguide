<?php

namespace App\Notifications\Apis\Tract\Sync;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Watched extends Notification
{
    use Queueable;

    protected $result;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $result)
    {
        $this->result = $result;
        $this->result['image_type'] = 'notifiable';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->result;
    }
}
