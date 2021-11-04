<?php
/**
 * Routes Authentication
 */

/** @var \Laravel\Lumen\Routing\Router $router */

// Auth E-mail
$router->post('/register', 'Auth\AuthController@register');
$router->post('/login', 'Auth\AuthController@login');

// Auth Facebook
$router->get('/facebook/redirect', 'Auth\AuthFacebookController@redirect');
$router->get('/facebook/callback', 'Auth\AuthFacebookController@callback');

// Auth Google
$router->get('/google/redirect', 'Auth\AuthGoogleController@redirect');
$router->get('/google/callback', 'Auth\AuthGoogleController@callback');

// Email Verification
$router->post('/email/send', 'Auth\Verification\VerifyEmailController@send');
$router->get('/email/verify', [
  'as' => 'verification.verify', 
  'uses' => 'Auth\Verification\VerifyEmailController@verify'
]);

// Password Reset
$router->post('/password/send', 'Auth\Password\RequestPasswordController@sendResetLinkEmail');
$router->post('/password/reset', 'Auth\Password\ResetPasswordController@reset');

$router->group(['middleware' => ['auth', 'role:admin|user']], function () use ($router) {
  $router->post('/logout', 'Auth\AuthController@logout');
  $router->post('/refresh', 'Auth\AuthController@refresh');
  $router->post('/me', 'Auth\AuthController@me');
});
