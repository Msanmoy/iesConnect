<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPassword
{
    /**
     * Construir el email personalizado.
     */
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]));

        return (new MailMessage)
            ->subject('Recuperación de contraseña - IESConnect')
            ->greeting('¡Hola!')
            ->line('Has solicitado restablecer tu contraseña en IESConnect.')
            ->action('Restablecer contraseña', $url)
            ->line('Este enlace caducará en 60 minutos.')
            ->line('Si no has solicitado este cambio, puedes ignorar este mensaje.')
            ->salutation('— El equipo de IESConnect');
    }
}
