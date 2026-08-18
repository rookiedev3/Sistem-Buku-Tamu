<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralEmailNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $messageContent;
    protected $actionUrl;

    public function __construct($title, $messageContent, $actionUrl = null)
    {
        $this->title = $title;
        $this->messageContent = $messageContent;
        $this->actionUrl = $actionUrl;
    }

    // Tentukan channel pengiriman ke 'mail'
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
                    ->subject($this->title)
                    ->greeting('Halo ' . $notifiable->name . ',')
                    ->line($this->messageContent);

        if ($this->actionUrl) {
            $mail->action('Lihat Detail', $this->actionUrl);
        }

        return $mail->line('Terima kasih telah menggunakan layanan kami.');
    }
}