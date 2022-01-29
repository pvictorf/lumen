<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerifyEmailMail extends Mailable {

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
    return $this->view('mail.verify_email', [
        "url" => $this->url,
        "user" => $this->user
    ]);
  }
}
