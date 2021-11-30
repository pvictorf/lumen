# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Installing
```bash
composer install
```

## Config
```bash
Copy env.example to .env and edit envirentments. 

php -S 127.0.0.1:8888 -t public/
```

## Routes
| Method | Endpoint        | Body                                                      | Header                      |
|--------|-----------------|-----------------------------------------------------------|-----------------------------|
| POST   | /register       | name, email, password, password_confirmation              | -                           |
| POST   | /email/send     | email                                                     | -                           |
| GET    | /email/verify   | url provided on email                                     | -                           |
| POST   | /login          | email, password                                           | -                           |
| POST   | /me             | -                                                         | Authorization: Bearer Token |
| POST   | /refresh        | -                                                         | Authorization: Bearer Token |
| POST   | /logout         | -                                                         | Authorization: Bearer Token |
| POST   | /password/send  | email                                                     | -                           |
| POST   | /password/reset | token(signature), email, password, password_confirmation  |                             |
| POST   | /file/upload    | Upload using chunks. (see: http://localhost:8888/file.example.html) |                   |   
| GET    | /facebook/redirect | Facebook login API                                     |                             |
| GET    | /facebook/callback | Facebook callback, login for webpages                  |                             |
| POST   | /facebook/authenticate | token from facebook, login for mobile apps (see: https://developers.facebook.com/apps/YOUR_APP_ID/roles/test-users/)|                          |
| GET    | /google/redirect | Google login API                                         |                             |
| GET    | /google/callback | Google callback, login for webpages                      |                             |

