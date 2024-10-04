<?php

namespace App\Notifications;

use App\Models\Despesa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreDespesaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $despesa;

    /**
     * Create a new notification instance.
     */
    public function __construct(Despesa $despesa)
    {
        $this->despesa = $despesa;
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
            ->subject('Despesa Cadastrada')
            ->greeting('Olá!')
            ->line('Uma nova despesa foi cadastrada.')
            ->line('Descrição: ' . $this->despesa->descricao)
            ->line('Valor: R$' . number_format($this->despesa->valor, 2, ',', '.'))
            ->line('Obrigado por utilizar nosso sistema!');
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
