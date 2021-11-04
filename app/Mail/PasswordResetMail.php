<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable {

  use Queueable, SerializesModels;

  private $url;
  private $user;
  

  public function __construct(string $url, $user)
  {
    $this->url = $url;
    $this->user = $user;
  }

  public function build() 
  {
    return;  // view;
  }
}