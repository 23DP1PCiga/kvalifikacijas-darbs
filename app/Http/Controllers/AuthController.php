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
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
    } else {
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Wrong password'
            ], 401);
        }
    }

   auth()->login($user);

    return response()->json([
        'message' => 'Logged in successfully',
        'user' => $user
    ]); 
}

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout()
{
    auth()->logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return response()->json([
        'message' => 'Logged out'
    ]);
}
}
