<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinalGradeCompleted extends Notification
{
    use Queueable;

    private $finalGrade;
    private $employees;

    public function __construct($finalGrade, $employees)
    {
        $this->finalGrade = $finalGrade;
        $this->employees = $employees;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting(' ')
                    ->subject('Рейтинг сотрудников')
                    ->markdown('mail.pharmacyEmail', [
                        'finalGrades' => $this->finalGrade,
                        'employees'=> $this->employees
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }
}
