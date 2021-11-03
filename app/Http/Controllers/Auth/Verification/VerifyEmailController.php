<?php

namespace App\Http\Controllers\Auth\Verification;

use App\Adapters\UrlGenerator;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{

  /**@var  App\Models\User */
  protected $user;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->broker = 'users';
  }

  /**
   * Create a new controller instance.
   *
   * @return App\Models\User
   */
  private function retriveUser($email)
  {
    $this->user = User::where('email', $email)->first();
    return $this->user;
  }

  /**
   * Request for email verification
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function send(Request $request)
  {
    $this->validate($request, ['email' => 'required|email']);

    $user = $this->retriveUser($request->email);

    if (!$user) {
      return response()->json(['error' => 'User not found'], 400);
    }

    $user->sendEmailVerificationNotification();

    return response()->json(['message' => 'The notification has been resubmitted']);
  }

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  protected function hasValidHash($id, $hash)
  {
    if (!hash_equals((string) $id, (string) sha1(md5($this->user->getKey())))) {
      return false;
    }

    if (!hash_equals((string) $hash, 
          sha1(md5($this->user->getEmailForVerification())))) {
      return false;
    }

    return true;
  }

  /**
   * Confirm email verification
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function verify(Request $request)
  {
    $url = new UrlGenerator(app());
    
    if(!$url->hasValidSignature($request)) {
      return response()->json(['message' => 'Invalid signature or link expired'], 401);
    }

    $user = $this->retriveUser(base64_decode($request->email));

    if(!$user) {
      return response()->json(['message' => 'User not found'], 400);
    }

    if (!$this->hasValidHash($request->id, $request->hash)) {
      return response()->json(['message' => 'Hash is invalid'], 400);
    }

    $this->fulfill($user);

    return AuthController::authenticateAndResponse($user);
  }

  /**
   * Fulfill the email verification request.
   *
   * @return void
   */
  private function fulfill($user)
  {
    if (!$user->hasVerifiedEmail()) {
      $user->markEmailAsVerified();

      event(new Verified($user));
    }

    return $user;
  }
}
