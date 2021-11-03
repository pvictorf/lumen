<?php

namespace App\Adapters;

use Elegant\Sanitizer\Sanitizer as ElegantSanitizer;

class Sanitizer extends ElegantSanitizer
{
  private $sanitizer; 

  public function __construct(array $data, array $filters)
  {
    $this->sanitizer = parent::__construct($data, $filters);
  }

  public static function make($data, array $rules)
  {
    return (new Sanitizer($data, $rules))->sanitize();
  }

  public function sanitizer()
  {
    return $this->sanitizer;
  }

}
