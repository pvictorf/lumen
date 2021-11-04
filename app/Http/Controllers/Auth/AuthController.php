<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Validators\AuthValidator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    /** 
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(User $user) {
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {

        $validator = AuthValidator::login($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $token = $this->generateToken($validator->validated());

        if(!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return self::responseWithToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        
        $validator = AuthValidator::register($request);

        if($validator->fails()){
            return response()->json(["error" => $validator->errors()->first()], 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => Hash::make($request->password)],
            ['email_confirmation' => md5(Str::random(60))],
        ))->assignRole('user'); 

        return response()->json([
            'message' => 'User successfully registered',
            'user' => [
                "name" => $user->name,
                "email" => $user->email,
            ]
        ], 201); 
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return self::responseWithToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me() {
        $user = auth()->user();
        return response()->json([
            "name" => $user->name,
            "email" => $user->email,
        ]);
    }

    private function generateToken($credentials) {
        $token = auth()
            ->setTTL(60*24*2) // Two days in minutes
            ->attempt($credentials, true);

        return $token;    
    } 

    public static function authenticateAndResponse($user) {
        $token = auth()
        ->setTTL(60*24*2) // Two days in minutes
        ->login($user);

        return self::responseWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'avatar' => auth()->user()->avatar,
            ]
        ]);
    }

    
}