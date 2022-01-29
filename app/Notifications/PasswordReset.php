<?php

namespace App\Notifications;

use App\Mail\PasswordResetMail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class PasswordReset extends Notification
{
    public $token;

    /**
     * The base url link sended to email
     *
     * @var string
     */
    private $sendurl;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->sendurl = env('FRONTEND_URL', env('APP_URL'));
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

   /**
   * Build the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
    public function toMail($notifiable)
    {
        $url = $this->sendurl . "/password/reset/{$this->token}?active=" . base64_encode($notifiable->email ?? '');

        return Mail::to($notifiable->email)
            ->send((new PasswordResetMail($url, $notifiable)));
    }
}
