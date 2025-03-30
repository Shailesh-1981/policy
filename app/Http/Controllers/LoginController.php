<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    // //
    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:6|confirmed',
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => bcrypt($request->password),
    //     ]);

    //     UserRoleModel::create([
    //         'user_id' => $user->id,
    //         'role_id' => 4,
    //     ]);

    //     $token = JWTAuth::fromUser($user);

    //     return response()->json(['user' => $user, 'token' => $token], 201);
    // }

    public function register(Request $request)
    {
        //there user register then its default value is user
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        UserRoleModel::create([
            'user_id' => $user->id,
            'role_id' => 4,
        ]);

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user_id= User::where('email',$request->email)->pluck('id');
        // i return user id because whene policy create there user_id required because user is exist if its create policy
        return response()->json([
            'status' => 'success',
            'message' =>'login success',
            'user_id'=>$user_id,
            'token' => $token
        ]);
    }
}
