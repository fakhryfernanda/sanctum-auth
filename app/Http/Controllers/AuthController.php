<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Mengambil user sesuai dengan email yang diberikan
        $user = User::query()
            ->where('email', $request->input('email'))
            ->first();

        // Cek apakah ada user yang memenuhi kriteria di atas
        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "email tidak ditemukan",
                "data" => null
            ]);
        }

        // Cek password
        if (!Hash::check($request->input("password"), $user->password)) {
            return response()->json([
                "status" => false,
                "message" => "password salah",
                "data" => null
            ]);
        }

        // Buat token untuk user
        $token = $user->createToken("auth_token");

        return response()->json([
            "status" => true,
            "message" => "",
            "data" => [
                "auth" => [
                    "token" => $token,
                    "token_type" => 'Bearer'
                ],
                "user" => $user
            ]
        ]);
    }

    public function getUser(Request $request)
    {
        $user = $request->user();
        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $user
        ]);
    }
}
