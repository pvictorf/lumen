<?php

namespace App\Http\Validators;

use App\Adapters\Sanitizer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthValidator extends Validator {

  public static function login(Request $request) {

    $data = Sanitizer::make($request->all(), [
      'email' => 'escape|trim|lowercase',
    ]);
    
    $validator = Validator::make($data, [
      'email' => 'required|string|email',
      'password' => 'required|string|min:6',
    ]);

    return $validator;
  }

  public static function register(Request $request) {

    $data = Sanitizer::make($request->all(), [
      'name' => 'escape|trim',
      'email' => 'escape|trim|lowercase',
    ]);

    $validator = Validator::make($data, [
      'name' => 'required|string|between:2,100',
      'email' => 'required|string|email|max:100|unique:users',
      'password' => 'required|string|confirmed|min:6',
    ]);

    return $validator;
  }

}