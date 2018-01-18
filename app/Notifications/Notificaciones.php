<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class Notificaciones extends Notification
{
    public function __construct($options = [])
    {
        $this->options = $options;
    }
    
    public function via($notifiable)
    {
        return ['mail'];
    }
    
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject($this->options['asunto'] ?? 'Sin Asunto')
        ->greeting($this->options['saludo'] ?? '')
        ->line($this->options['toplinea'] ?? '')
        ->action($this->options['link'] ?? config('app.name'), $this->options['href'] ?? route('home'))
        #->line($this->options['btnlinea'] ?? '')
        ->salutation(config('app.name'))
        ->markdown('vendor.notifications.email',[]);
    }
}