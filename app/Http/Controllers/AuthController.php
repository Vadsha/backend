<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|string|confirmed|max:16'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $token = $user->createToken('chatting')->plainTextToken;
        return response()->json([
            'data' => ['user' => $user, 'token' => $token],
            'errors' => [],
            'condition' => true
        ]);

    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {

            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('chatting')->plainTextToken;
                return response()->json([
                    'data' => ['user' => $user, 'token' => $token],
                    'errors' => [],
                    'condition' => true
                ]);
            } else {

                return response()->json([
                    'data' => [],
                    'errors' => "password does not match",
                    'condition' => false
                ]);
            }
        } else {
            return response()->json([
                'data' => [],
                'errors' => "there is no user with this email",
                'condition' => false
            ]);
        }

        $token = $user->createToken('chatting')->plainTextToken;
    }
}
