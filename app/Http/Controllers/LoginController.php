<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\ErrorResource;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function store (Request $request){
        $data = $request->validate([
            "username" => "required|string|max:255",
            "password" => "required|string",
        ]);

        $user = User::where('username', $data['username'])->first();

        if ($user){

            $token = $user->createToken('token_base_name')->plainTextToken;

            return new UserResource([
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username'=> $user->username,
                'email'=> $user->email,
                'token' => $token,
                'created_at' => $user->created_at,
                'updated_at'=> $user->updated_at
            ]);
        }

        return new ErrorResource([
            'status_code' => 500,
            'error_code' => 2002,
            'message' => 'خطای ناشناخته ای رخ داد',
        ]);
    }
}
