<?php

namespace App\Http\Controllers\Auth;

use App\Adapters\JWTAuthentication;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;


class AuthFacebookController extends Controller
{
  public function redirect(Request $request)
  {
    return Socialite::driver('facebook')->stateless()->redirect();
  }


  public function callback(Request $request)
  {
    try {
      $social = Socialite::driver('facebook')->stateless()->user();

      $user = $this->createOrLoginUser($social);

      $token = JWTAuthentication::login($user);

      return redirect( env('FRONTEND_URL') . "?tk={$token}&name={$user->name}");

    } catch(\Throwable $th) {

      return redirect( env('FRONTEND_URL') . "/fail");

    }
  }

  /**
   * Authenticate user using Facebook token
   * 
   * https://stackoverflow.com/questions/65142862/log-in-users-in-flutter-through-social-accounts-with-laravel-socialite-backend?answertab=active#tab-top
   * 
   * @param Request $request
   * @return void
  */
  public function authenticate(Request $request) 
  {
    $this->validate($request, [
      'token' => 'required|string'
    ]);

    $token = $request->token; 

    try {

      $social = Socialite::driver('facebook')->stateless()->userFromToken($token);

      $user = $this->createOrLoginUser($social);

      $token = JWTAuthentication::login($user);

      return AuthController::responseWithToken($token);

    } catch(\Throwable $th) {
      return response()->json([
        "message" => "Falied login with Facebook",
        "error" => $th->getMessage(),
      ], 400);
    }
  }

  /**
  * Create a new user or login existent user
  *
  * @param object $social
  * @return User
  */
  private function createOrLoginUser($social) 
  {
    $user = User::query()
      ->where('facebook_id', '=', $social->id)
      ->orWhere('email', '=', $social->email)
      ->first(); 

    if($user) {
      $user->facebook_id = $social->id;
      $user->save();
      
      return $user;
    } 

    $user = User::create([
      'facebook_id' => $social->id,
      'name' => $social->name,
      'email' => $social->email,
      'avatar' => $social->avatar,
      'password' => Hash::make(random_bytes(10)),
    ])->assignRole('user');

    return $user;
  }
}