<?php
/**
 * Routes FileUploader
 */

/** @var \Laravel\Lumen\Routing\Router $router */

$router->post('/file/upload', 'FileUploaderController@store');

$router->post('/file/download', 'FileUploaderController@show');