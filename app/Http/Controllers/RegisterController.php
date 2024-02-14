<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function store (Request $request){
        $data = $request->validate([
            "first_name" => 'required|string|max:255',
            "last_name" => "required|string|max:255",
            "username" => "required|string|max:255|unique:users,username",
            "email" => "required|email|unique:users,email",
            "password" => "required|string",
        ]);

        $result = User::create([
            "first_name" => $data['first_name'],
            "last_name" => $data['last_name'],
            "username" => $data["username"],
            "email" => $data['email'],
            "password" => bcrypt($data["password"]),
        ]);

        if ($result) {
            $token = $result->createToken('token_base_name')->plainTextToken;
            return new UserResource([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'username'=> $data['username'],
                'email'=> $data['email'],
                'token' => $token,
                'created_at' => $result->created_at,
                'updated_at'=> $result->updated_at
            ]);
        }

        return new ErrorResource([
            'status_code' => 500,
            'error_code' => 2002,
            'message' => 'خطای ناشناخته ای رخ داد',
        ]);
    }
}
