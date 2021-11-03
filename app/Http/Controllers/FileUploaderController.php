<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class FileUploaderController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  public function index()
  {
  }

  public function store(Request $request)
  {
    $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

    if (!$receiver->isUploaded()) {
      return response()->json(["error" => "Falied upload file"], 400);
    }

    $fileReceived = $receiver->receive();

    if ($fileReceived->isFinished()) { 
      $file = $fileReceived->getFile(); 

      $fileInfo = $this->storageFile($file);

      unlink($file->getPathname());

      return response()->json([$fileInfo], 201);
    }
      
    return $this->responseWithPercentage($fileReceived);
  }

  private function storageFile($file) {
    $extension = $file->getClientOriginalExtension();
    $fileName = str_replace(".{$extension}", '', $file->getClientOriginalName()); 
    $fileName .= '_' . md5(time()) . ".{$extension}"; 

    $disk = Storage::disk(config('filesystems.default'));
    $path = $disk->put('anexos', $file);

    return (object) [
      'path' => url($path),
      'fileName' => $fileName
    ];
  }

  private function responseWithPercentage($fileReceived) {
    $handler = $fileReceived->handler();

    return response()->json([
      'done' => $handler->getPercentageDone(),
      'status' => true
    ]);
  }
}
