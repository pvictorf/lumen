<?php

namespace App\Http\Validators;

use App\Adapters\Sanitizer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FileValidator extends Validator {

  /**
   * Mime types
   * 
   * https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
   */

  public static function upload(Request $request) {

    $data = $request->all(); 

    $validator = Validator::make($data, [
      'file' => 'required|mimes:jpg,png,bmp,doc,docx,pdf,csv,txt,xls,ods,odt,zip,rar'
    ]);
 
    return $validator;
  }

}  