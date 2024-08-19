<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    public function UserRegistration(Request $request) {
        try {
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successful'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User Registration Failed for Internal Error'
            ], 500);
        }
    }

    public function UserLogin(Request $request) {
        $count = User::where('email', $request->input('email'))
                        ->where('password', $request->input('password'))
                        ->count();
        
        if ($count == 1) {
            $token = JWTToken::CreateToken($request->input('email'));

            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successful',
                'token' => $token,
            ], 200);
        } 
        else {
            return response()->json([
               'status' => 'failed',
               'message' => 'Unauthorized',
            ]);
        }
    }
}
