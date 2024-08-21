<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;

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
            ], 401);
        }
    }

    public function SendOTPCode(Request $request) {
        $email = $request->input('email');
        $otp = rand(100000,999999);

        $count = User::where('email', $email)->count();

        if ($count == 1) {
            // OTP Send to Email
            Mail::to($email)->send(new OTPMail($otp));
            // OTP store on Database Table
            User::where('email', $email)->update(['otp' => $otp]);
            return response()->json([
               'status' => 'success',
                'message' => 'OTP Send Successful',
            ]);
        } else {
            return response()->json([
               'status' => 'failed',
                'message' => 'User Unauthorized',
            ], 401);
        }
    }

    public function VerifyOTP(Request $request) {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', $email)->where('otp', $otp)->count();

        if($count == 1) {
            // Update OTP in Database
            User::where('email', $email)->update(['otp' => ""]);
            // Create Token
            $token = JWTToken::CreateTokenForResetPassword($request->input('email'));
            return response()->json([
               'status' => 'success',
               'message' => 'OTP Verify Successful',
               'token' => $token,
            ]);
        }else {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Unauthorized',
            ], 401);
        }
    }

    public function ResetPassword(Request $request) {
        try {
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email', $email)->update(['password' => $password]);
            return response()->json([
                'status' => 'success',
                'message' => 'Password Reset Successful',
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Password Reset Failed for Internal Error',
            ], 500);
        }
    }

}
