<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/files.php';


$router->get('/', function () use ($router) {
    return $router->app->version();
});

