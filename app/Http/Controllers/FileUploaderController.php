<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class FileUploaderController extends Controller
{

  public function upload(Request $request)
  {
    $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

    if ($receiver->isUploaded() === false) {
      throw new UploadMissingFileException();
    }

    $fileReceived = $receiver->receive();

    if ($fileReceived->isFinished()) {
      $file = $fileReceived->getFile();

      $fileInfo = $this->saveFile($file);

      unlink($file->getPathname());

      return response()->json([$fileInfo], 201);
    }

    return $this->responseWithPercentage($fileReceived);
  }


  private function responseWithPercentage($fileReceived)
  {
    $handler = $fileReceived->handler();

    return response()->json([
      'done' => $handler->getPercentageDone(),
      'status' => true
    ]);
  }

  private function saveFile($file)
  {
    $fileName = $this->createFilename($file);

    $disk = Storage::disk(config('filesystems.default'));
    $path = $disk->put('anexos', $file);

    return (object) [
      'path' => url($path),
      'fileName' => $fileName
    ];
  }

  /**
   * Saves the file to S3 server
   *
   * @param UploadedFile $file
   *
   * @return JsonResponse
   */
  protected function saveFileToS3($file)
  {
    $fileName = $this->createFilename($file);

    $disk = Storage::disk('s3');

    $disk->put('anexps', $file);

    // for older laravel
    // $disk->put($fileName, file_get_contents($file), 'public');
    $mime = str_replace('/', '-', $file->getMimeType());

    // We need to delete the file when uploaded to s3
    unlink($file->getPathname());

    return response()->json([
      'path' => $disk->url($fileName),
      'name' => $fileName,
      'mime_type' => $mime
    ]);
  }

  /**
   * Create unique filename for uploaded file
   * @param UploadedFile $file
   * @return string
   */
  protected function createFilename($file)
  {
    $extension = $file->getClientOriginalExtension();
    $filename = str_replace("." . $extension, "", $file->getClientOriginalName()); // Filename without extension

    // Add timestamp hash to name of the file
    $filename .= "_" . md5(time()) . "." . $extension;

    return $filename;
  }
}
