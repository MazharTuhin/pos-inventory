<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

use function Ramsey\Uuid\v1;

class UserController extends Controller
{
    // Frontend

    public function LoginPage(): View {
        return view('pages.auth.login-page');
    }

    public function RegistrationPage(): View {
        return view('pages.auth.registration-page');
    }

    public function SendOtpPage(): View {
        return view('pages.auth.send-otp-page');
    }

    public function VerifyOtpPage(): View {
        return view('pages.auth.verify-otp-page');
    }

    public function ResetPasswordPage(): View {
        return view('pages.auth.reset-password-page');
    }

    public function DashboardPage(): View {
        return view('pages.dashboard.dashboard');
    }

    public function ProfilePage(): View {
        return view('pages.dashboard.profile-page');
    }



    public function UserRegistration(Request $request) {
        try {
            $validated = $request->validate([
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'email' => 'required|email|unique:users',
                'mobile' => 'required|numeric',
                'password' => 'required|string',
            ]);

            User::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'User  created successfully'
            ], 200);
        }
        catch (ValidationException $e) {
            // Catch validation exception and return validation errors
            return response()->json([
                'status' => 'failed',
                'errors' => $e->errors(),
            ], 422);
        }
        catch (Exception $e) {
            // Catch any other exceptions
            return response()->json([
                'status' => 'failed',
                'message' => 'Request Failed for Internal Error',
            ], 500);
        }
    }

    public function UserLogin(Request $request) {
        $validator = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', $email)->where('password', $password)->first();

        if($user) {
            $token = JWTToken::CreateToken($email, $user->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successfull',
            ], 200)->cookie('token', $token, 60*24*30);
        }
        else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ], 401);
        }
    }

    public function SendOTP(Request $request) {
        $email = $request->input('email');
        Validator::validate($request->all(), [
            'email' => 'required|email',
        ]);

        $user = User::where('email', $email)->first();

        if ($user) {
            // Generate random otp
            $otp = rand(100000, 999999);
            // otp send to user email
            Mail::to($email)->send(new OTPMail($otp));
            // Update value of otp column
            User::where('email', $email)->update(['otp' => $otp]);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP code send successfully',
            ], 200);
        }
        else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ], 401);
        }
    }

    public function VerifyOTP(Request $request) {
        $email = $request->input('email');
        $otp = $request->input('otp');
        Validator::validate($request->all(), [
            'otp' => 'required|numeric|min:6',
        ]);

        $user = User::where('email', $email)->where('otp', $otp)->first();

        if ($user) {
            // Set Condition that OTP vilid for 5 minutes
            if (Carbon::now()->diffInMinutes($user->updated_at) <= 5) {
                User::where('email', $email)->update(['otp' => ""]);
                $token = JWTToken::CreateTokenForResetPassword($email);
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP Verification Successful',
                ], 200)->cookie('token', $token, 60*15);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'OTP has expired'
                ]);
            }
        }
        else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ], 401);
        }
    }

    public function ResetPassword(Request $request) {
        try {
            $email = $request->header('email');
            $password = $request->input('password');
            $password_confirmation = $request->input('password_confirmation');

            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $updated = User::where('email', $email)->update(['password' => $password]);
            if ($updated) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Password Reset Successful',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'User not found',
                ], 404);
            }
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Password Reset Failed for Internal Error',
            ], 500);
        }
    }

    public function UserLogout(Request $request) {
        return response()->json([
            'status' => 'success',
            'message' => 'User Logout Successful',
        ])->cookie('token', null, -1);
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
            $validator = Validator::make($request->all(), [
                'firstName' => 'required|string|max:50',
                'lastName' => 'required|string|max:50',
                'mobile' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'password' => 'sometimes|string|min:6',
            ]);
            User::where('email', $email)->update($request->only(['firstName', 'lastName', 'mobile', 'password']));
            return response()->json([
                'status' => 'success',
                'message' => 'User Profile Update Successful',
            ]);
        } catch (ValidationException $e) {
            // Catch validation exception and return validation errors
            return response()->json([
                'status' => 'failed',
                'errors' => $e->errors(),
            ], 422);
        }
        catch (Exception $exception) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Request Failed for Internal Error',
            ]);
        }
    }

}
