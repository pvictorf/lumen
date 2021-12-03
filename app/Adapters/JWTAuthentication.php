<?php

namespace App\Adapters;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class JWTAuthentication {

    /**
     * Define token expiration time in minutes
     * Two days in minutes is iqual (60*24*2)
     */
    const TOKEN_EXPIRES_MINUTES = 2880;


    /**
     * Try retrive user by token
     *
     * @return User|null
     */
    public static function authenticate() 
    {
       return FacadesJWTAuth::parseToken()->authenticate();
    }

    /**
     * Generate JWT Token
     *
     * @param array|object $credentials
     * @param int $expires
     * @return string
     */
    public static function generateToken($credentials, $expires = self::TOKEN_EXPIRES_MINUTES) 
    {
       $token = auth()
        ->setTTL($expires) 
        ->attempt($credentials, true);

       return $token ?? ''; 
    }

    /**
     * Refresh JWT Token
     *   
     * @return string
     */
    public static function refreshToken() 
    {
        $token = auth()->refresh();
        return $token ?? ''; 
    }


    /**
    * Login the user
    *
    * @param User|object $user
    * @param int $expires
    * @return string
    */
    public static function login($user, $expires = self::TOKEN_EXPIRES_MINUTES) 
    {
        $token = auth()
        ->setTTL($expires) 
        ->login($user);

        return $token ?? ''; 
    }


    /**
     * Logout revoking the current token
     *
     * @return bool
     */
    public static function logout() 
    {
        try {
            auth()->logout();
            return true;
        } catch(\Throwable $th) {
            return false;
        }
    }


    /**
     * Retrive the current user
     *
     * @return User
     */
    public static function user() 
    {
        return auth()->user();
    }

    /**
     * Expires time based on TTL
     *
     * @return int
     */
    public static function expiresIn() 
    {
        return auth()->factory()->getTTL() * 60;
    }

}