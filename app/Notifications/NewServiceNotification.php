<?php

namespace App\Notifications;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewServiceNotification extends Notification
{
    use Queueable;

    public function __construct(private Service $service)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New service available',
            'message' => $this->service->name.' has been added to the clinic services.',
            'service_id' => $this->service->id,
            'url' => route('services.index'),
        ];
    }
}
