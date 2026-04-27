<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(private Appointment $appointment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New appointment assigned',
            'message' => 'You have a new appointment with '.$this->appointment->patient->name
                .' on '.$this->appointment->appointment_date
                .' at '.$this->appointment->appointment_time.'.',
            'appointment_id' => $this->appointment->id,
            'url' => route('appointments.index'),
        ];
    }
}
