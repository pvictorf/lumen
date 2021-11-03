<?php
/**
 * Routes FileUploader
 */

/** @var \Laravel\Lumen\Routing\Router $router */

$router->post('/file/upload', 'FileUploaderController@upload');

$router->post('/file/download', 'FileDownloaderController@download');