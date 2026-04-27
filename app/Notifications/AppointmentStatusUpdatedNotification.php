<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Appointment $appointment,
        private User $updatedBy,
        private bool $sendEmail = false
    ) {
    }

    public function via(object $notifiable): array
    {
        return $this->sendEmail ? ['database', 'mail'] : ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->statusLabel();

        return (new MailMessage)
            ->subject('Appointment '.$status)
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your appointment has been '.$status.'.')
            ->line('Doctor: '.$this->appointment->doctor->name)
            ->line('Service: '.$this->appointment->service->name)
            ->line('Date: '.$this->appointment->appointment_date)
            ->line('Time: '.$this->appointment->appointment_time)
            ->action('View appointments', route('appointments.index'));
    }

    public function toArray(object $notifiable): array
    {
        $status = $this->statusLabel();

        return [
            'title' => 'Appointment '.$status,
            'message' => 'Your appointment with '.$this->appointment->doctor->name
                .' has been '.$status.' by '.$this->updatedBy->name.'.',
            'appointment_id' => $this->appointment->id,
            'status' => $this->appointment->status,
            'url' => route('appointments.index'),
        ];
    }

    private function statusLabel(): string
    {
        return $this->appointment->status === 'confirmed' ? 'accepted' : 'cancelled';
    }
}
