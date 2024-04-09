<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $username = $request->username;
        $password = $request->password;

        if (empty($username) || empty($password)) {
            return response()->json(['message' => 'Jaza nafasi zote zilizo wazi.'], 401);
        }
        $user = User::where("username", $username)->where("active", true)->first();

        if ($user) {
            $passwordDatabase = $user->password;
            if ($password == $passwordDatabase) {
                // Password matches, do something (e.g., log in the user)
                $token = $user->createToken('main')->plainTextToken;
                return response((compact('user', 'token')));
            } else {
                // Invalid username or password
                return response()->json(['message' => 'Umekosea jina au password'], 401);
            }
        }else{
            return response()->json(['message' => 'Tumia jina au password halisi'], 401);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json(['message'=> 'logout'],200);
    }
}
