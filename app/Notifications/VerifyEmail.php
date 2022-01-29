<?php

namespace App\Notifications;

use App\Adapters\UrlGenerator;
use App\Mail\VerifyEmailMail;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;

class VerifyEmail extends Notification
{
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
    public function __construct()
    {
      $this->sendurl = env('FRONTEND_URL', url('email/verify/'));
    }

    /**
     * The callback that should be used to create the verify email URL.
     *
     * @var \Closure|null
     */
    public static $createUrlCallback;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

     /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable);
        }

        $url = new UrlGenerator(app());

        $sendUrl = $url->temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
              'id' => sha1(md5($notifiable->getKey())),
              'hash' => sha1(md5($notifiable->getEmailForVerification())),
              'active' => base64_encode($notifiable->getEmailForVerification())
            ]
        );

        return str_replace(env('APP_URL'), $this->sendurl, $sendUrl);
    }

    /**
     * Set a callback that should be used when creating the email verification URL.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }


    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return Mail::to($notifiable->email)
            ->send((new VerifyEmailMail($verificationUrl, $notifiable)));
    }

}
