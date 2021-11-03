<?php

namespace App\Http\Validators;

use App\Adapters\Sanitizer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class VerifyEmailValidator extends Validator {

  public static function verify(Request $request) {

    $validator = Validator::make($request->all(), [
      'email' => 'required',
      'id' => 'required',
      'hash' => 'required',
      'signature' => 'required',
      'expires' => 'required'
    ]);

    return $validator;

  }
  
}

