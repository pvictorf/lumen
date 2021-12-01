<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;


class AuthGoogleController extends Controller
{
  public function redirect(Request $request)
  {
    return Socialite::driver('google')->stateless()->redirect();
  }
  

  public function callback(Request $request)
  {
    try {
      $social = Socialite::driver('google')->stateless()->user();

      $user = $this->createOrLoginUser($social);

      return AuthController::authenticateAndResponse($user);

    } catch(\Throwable $th) {
      return response()->json([
        "message" => "Falied login with facebook",
        "error" => $th->getMessage(),
      ], 400);
    }
  }


  public function authenticate(Request $request) 
  {
    $this->validate($request, [
      'token' => 'required|string'
    ]);

    $token = $request->token; 

    try {

      $social = Socialite::driver('google')->stateless()->userFromToken($token);

      $user = $this->createOrLoginUser($social);

      return AuthController::authenticateAndResponse($user);
      
    } catch(\Throwable $th) {
      return response()->json([
        "message" => "Falied login with Google",
        "error" => $th->getMessage(),
      ], 400);
    }
  }


  private function createOrLoginUser($social) 
  {
    $user = User::where([
      'google_id' => $social->id,
    ])->first();

    if(!$user) {
      $user = User::create([
        'google_id' => $social->id,
        'name' => $social->name,
        'email' => $social->email,
        'avatar' => $social->avatar,
        'password' => Hash::make(random_bytes(10)),
      ])->assignRole('user');
    }

    return $user;
  }
}