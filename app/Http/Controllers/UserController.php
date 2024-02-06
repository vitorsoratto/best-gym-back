<?php

namespace App\Http\Controllers;

use App\Models\User;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $email = $request->email;
        $password = $request->password;
        if (!$token = Auth::attempt(['email' => $email, 'password' => $password])) {
            abort(401, 'Unauthorized');
        }

        return response()->json([
            'token' => $token,
            'type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function update_user(Request $request)
    {
        $user = $request->user();

        $data = $request->all();

        if ($user->role == 'admin') {
            $user->role = $data['role'] ?? $user->role;
        }

        $user->name = $data['name'] ?? $user->name;

        $user->update();

        return response()->json([
            'message' => 'Successfully updated user!',
            'user' => $user
        ]);
    }

    public function make_admin(Request $request)
    {
        $user = $request->user();

        $user->role = 'admin';

        $user->update();

        return response()->json([
            'message' => 'Successfully updated user!',
            'user' => $user
        ]);
    }
}
