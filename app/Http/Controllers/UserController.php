<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{
    // Frontend
    public function LoginPage():View {
        return view('pages.auth.login-page');
    }

    public function RegistrationPage():View {
        return view('pages.auth.registration-page');
    }

    public function SendOtpPage():View {
        return view('pages.auth.send-otp-page');
    }

    public function VerifyOtpPage():View {
        return view('pages.auth.verify-otp-page');
    }

    public function ResetPasswordPage():View {
        return view('pages.auth.reset-pass-page');
    }

    public function ProfilePage():View {
        return view('pages.dashboard.profile-page');
    }


    // Backend
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
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'User Registration Failed for Internal Error'
            ], 500);
        }
    }

    public function UserLogin(Request $request) {
        $count = User::where('email', $request->input('email'))
                        ->where('password', $request->input('password'))
                        ->select('id')->first();

        if ($count !== null) {
            $token = JWTToken::CreateToken($request->input('email'), $count->id);

            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successful',
            ], 200)->cookie('token', $token, time()+60*24*30);
        }
        else {
            return response()->json([
               'status' => 'failed',
               'message' => 'unauthorized',
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
            ])->cookie('token', $token, 60*24*30);
        }else {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Unauthorized',
            ], 500);
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

    public function UserLogout() {
        return redirect('/userLogin')->cookie('token', '', '-1');
    }

    public function UserProfile(Request $request) {
        $email = $request->header('email');
        $user = User::where('email', $email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'User Profile Request Successful',
            'data' => $user,
        ], 200);
    }

    public function UpdateProfile(Request $request) {
        try {
            $email = $request->header('email');
            $firstName = $request->input('firstName');
            $lastName = $request->input('lastName');
            $mobile = $request->input('mobile');
            $password = $request->input('password');
            User::where('email', $email)->update([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'mobile' => $mobile,
                'password' => $password,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'User Profile Update Successful',
            ]);
        }
        catch (Exception $exception) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Request Failed for Internal Error',
            ]);
        }
    }

}
