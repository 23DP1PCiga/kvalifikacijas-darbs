<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:30',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'reading_goal' => 0,
            'books_read' => 0,
        ]);

        auth()->login($user);

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user
        ]);
    }
}
