<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Otp; // Assuming you have an Otp model for storing OTPs

class OtpAuthController extends Controller
{
    // public function sendOtp(Request $request){
    //     $request->validate(['phone'=> 'required']);


    //     $otp = rand(100000, 999999);
    //     $user = User::updateOrCreate(
    //         ['phone' => $request->phone],
    //         ['otp' => $otp, 'otp_expires_at' => Carbon::now()->addMinutes(5)]
    //     );


    //     return response()->json(['message'=> 'OTP sent successfully', 'otp' => $otp], 200);
    // }



    public function sendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/']);

        // $otp = rand(100000, 999999);
        $otp = 123456;

        Otp::Create(
            [
                'phone' => $request->phone,

                'otp' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(5)
            ],

        );

        return response()->json(['message' => 'OTP sent successfully', 'phone' => $request->phone, 'otp' => $otp], 200);

    }

    // public function verifyOtp(Request $request){
    //     $request->validate([
    //         'phone' => 'required',
    //         'otp' => 'required|digits:6',
    //     ]);

    //     $user = User::where('phone', $request->phone)
    //                 ->where('otp', $request->otp)
    //                 ->where('otp_expires_at', '>', now())
    //                 ->first();

    //     if (!$user) {
    //         return response()->json(['message' => 'Invalid or expired OTP'], 401);
    //     }

    //     // Clear OTP after successful verification
    //     $user->otp = null;
    //     $user->otp_expires_at = null;
    //     $user->save();

    //     // Generate JWT token
    //     $token = JWTAuth::fromUser($user);

    //    return response()->json([
    //         'token' => $token,
    //         'user' => $user
    //     ]);
    // }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'otp' => 'required|digits:6',
        ]);

        $otpRecord = Otp::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json(['message' => 'Invalid or expired OTP'], 401);
        }

        // Clear OTP after successful verification
        // $otpRecord->delete();

        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => null, // or default value
                'email' => null,
            ]
        );


        $token = JWTAuth::fromUser($user);
        return response()->json([
            'message' => 'OTP verified successfully',
            'token' => $token,
            'user' => $user
        ]);
    }


    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json(['message' => 'User logged out successfully'], 200);
    }
}
