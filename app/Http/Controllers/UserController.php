<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User as UserResource;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('identity', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json(compact('token', 'user'))
            ->withCookie(
                'token',
                $token,
                config('jwt.ttl'), // ttl => time to live
                '/', // path
                null, // domain
                config('app.env') !== 'local', // Secure
                true, // httpOnly
                false, //
                config('app.env') !== 'local' ? 'None' : 'Lax' // SameSite
            );
    }

    public function register(Request $request){
        $request->validate([
            'identity' => 'required|string|max:10',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'identity' => $request->identity,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json(new UserResource($user, $token), 201)
            ->withCookie(
                'token',
                $token,
                config('jwt.ttl'),
                '/',
                null,
                config('app.env') !== 'local',
                true,
                false,
                config('app.env') !== 'local' ? 'None' : 'Lax'
            );
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ])
            ->withCookie('token', null,
            config('jwt.ttl'),
            '/',
            null,
            config('app.env') !== 'local',
            true,
            false,
            config('app.env') !== 'local' ? 'None' : 'Lax'
        );
    }

    public function me()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
