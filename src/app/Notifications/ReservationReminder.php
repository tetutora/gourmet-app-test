<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationReminder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('予約リマインダー')
            ->line('以下の予約があります。')
            ->line('予約者: ' . $this->reservation->user->name)
            ->line('予約日: ' . Carbon::parse($this->reservation->reservation_date)->format('Y年m月d日'))
            ->line('予約時間: ' . Carbon::parse($this->reservation->reservation_time)->format('H:i'))
            ->line('来店人数: ' . $this->reservation->num_people . '名')
            ->line('ご来店をお待ちしております。');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
