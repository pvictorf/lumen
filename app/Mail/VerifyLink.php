<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerifyLink extends Mailable {

  private $user;

  use Queueable, SerializesModels;

  public function __construct(User $user)
  {
    $this->user = $user;
  }

  public function build() 
  {
    $hash =  base64_encode(json_encode([
      "email" => $this->user->email,
      "email_confirmation" => $this->user->email_confirmation,
    ]));

    $link = URL::to('/') . "/confirm/{$hash}";

    return $this->view('mail.verifylink', ["link" => $link]);
  }
}