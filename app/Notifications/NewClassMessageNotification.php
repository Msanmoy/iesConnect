<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewClassMessageNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $link;

    public function __construct($message, $link)
    {
        $this->message = $message;
        $this->link = $link;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Nuevo mensaje en clase',
            'message' => $this->message,
            'link' => $this->link,
        ];
    }
}
