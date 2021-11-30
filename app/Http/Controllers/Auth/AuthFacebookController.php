<?php

namespace App\Http\Controllers\Auth;

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

      return AuthController::authenticateAndResponse($user);

    } catch(\Throwable $th) {
      return response()->json([
        "message" => "Falied login with facebook",
        "error" => $th->getMessage(),
      ], 400);
    }
  }

  // https://stackoverflow.com/questions/65142862/log-in-users-in-flutter-through-social-accounts-with-laravel-socialite-backend?answertab=active#tab-top
  public function authenticate(Request $request) 
  {
    $this->validate($request, [
      'token' => 'required|string'
    ]);

    $token = $request->token; 

    $social = Socialite::driver('facebook')->stateless()->userFromToken($token);

    $user = $this->createOrLoginUser($social);

    return AuthController::authenticateAndResponse($user);
  }


  private function createOrLoginUser($social) 
  {
    $user = User::where([
      'facebook_id' => $social->id,
    ])->first();

    if(!$user) {
      $user = User::create([
        'facebook_id' => $social->id,
        'name' => $social->name,
        'email' => $social->email,
        'avatar' => $social->avatar,
        'password' => Hash::make(random_bytes(10)),
      ])->assignRole('user');
    }

    return $user;
  }
}