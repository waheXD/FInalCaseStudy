<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    private $users;

    public function __construct()
    {
        $this->users = new User;
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:users',
                'password' => 'required|min:6',
            ],
            [
                'exists.exists' => "The email wasn't registered.",
                'password.min' => 'The password must be at least 6 characters.',
            ]
        );

        $user = $this->users::where('email', $request->email)->first();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token, 'user_id' => $user->id, 'account_type' => $user->account_type], 200);
        }

        return response()->json(['message' => 'Invalid username or password'], 401);
    }
}
