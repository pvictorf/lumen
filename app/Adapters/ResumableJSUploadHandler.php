<?php

namespace App\Adapters;

class ResumableJSUploadHandler extends \Pion\Laravel\ChunkUpload\Handler\ResumableJSUploadHandler
{
    public static function canUseSession()
    {
        return false;
    }
}