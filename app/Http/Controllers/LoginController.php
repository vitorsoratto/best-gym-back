<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {

            $user = $request->user();

            $token = $user->createToken('access_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
            ]);
        }

        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }
}
