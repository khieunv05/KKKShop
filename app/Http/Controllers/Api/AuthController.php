<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (method_exists($user, 'createToken')) {
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(['token' => $token], Response::HTTP_CREATED);
        }

        return response()->json([
            'message' => 'User created. Install and configure Laravel Sanctum to issue API tokens.'
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        if (method_exists($user, 'createToken')) {
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(['token' => $token]);
        }

        return response()->json([
            'message' => 'Authenticated. Install and configure Laravel Sanctum to issue API tokens.'
        ]);
    }
}
