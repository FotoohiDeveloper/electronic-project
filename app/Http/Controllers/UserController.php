<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        return new UserResource($user);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $user_id = $user->id;
        $username = $user->username;
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,$user_id",
            'username' => "required|string|max:255|unique:users,username,$username",
        ]);

        $result = $user->update($data);
        if ($result) {
            return new SuccessResource([
                'status_code' => 202,
                'success_code' => 1001,
                'message' => 'کاربر گرامی حساب شما با موفقیت آپدیت شد'
            ]);
        }

        return new ErrorResource([
            'status_code' => 500,
            'error_code' => 2001,
            'message' => 'خطای ناشناخته ای رخ داد',
        ]);
    }

    //TODO Change Password
}
