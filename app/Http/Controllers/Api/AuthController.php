<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Otp;

class AuthController extends Controller
{
    public function generateOtp(Request $request)
{
    // Validate the request
    $request->validate([
        'phone' => 'nullable|digits:8|required_without:email',
        'email' => 'nullable|email|required_without:phone',
        'password' => 'required',
    ]);

    // Find the user by phone or email
    $user = User::where('phone', $request->phone)
                ->orWhere('email', $request->email)
                ->first();
// dd($user);
    // Check if user exists and password matches
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['status' => false, 'message' => 'Invalid phone/email or password']);
    }

    // Generate OTP
    $otp = rand(100000, 999999);
    $expiresAt = now()->addMinutes(5);

    // Store OTP in the database (phone or email as the identifier)
    Otp::updateOrCreate(
        [
            'phone' => $request->phone ?? $user->phone,
            'email' => $request->email ?? $user->email,
        ],
        [
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]
    );

    // Simulate sending OTP (replace with an actual SMS/Email API)
    // SendOtpService::send($request->phone ?? $request->email, $otp);

    return response()->json([
        'status' => true,
        'message' => 'OTP generated successfully',
        'otp' => $otp, // Include this for testing purposes only
    ]);
}


    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'phone' => 'required|digits:8',
    //         'otp' => 'required|digits:6',
    //     ]);

    //     $otpRecord = Otp::where('phone', $request->phone)
    //         ->where('otp', $request->otp)
    //         ->where('expires_at', '>=', now())
    //         ->first();

    //     if (!$otpRecord) {
    //         return response()->json([ 'status' => false,'message' => 'Invalid or expired OTP']);
    //     }

    //     // Retrieve or create the user
    //     $user = User::firstOrCreate(['phone' => $request->phone], [
    //         'password' => bcrypt('default_password'), // Replace this with a secure default password if needed
    //     ]);

    //     // Delete the OTP after successful verification
    //     $otpRecord->delete();

    //     // Generate Sanctum token
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'OTP verified successfully',
    //         'token' => $token,
    //         'user' => $user,
    //     ]);
    // }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|digits:8|required_without:email',
            'email' => 'nullable|email|required_without:phone',
            'otp' => 'required|digits:6',
        ]);
    
        // Find the OTP record using Eloquent query
        $otpRecord = Otp::where(function ($query) use ($request) {
            if ($request->phone) {
                $query->where('phone', $request->phone);
            }
            if ($request->email) {
                $query->orWhere('email', $request->email);
            }
        })
            ->where('otp', $request->otp)
            ->where('expires_at', '>=', now())
            ->first();
    
        // Check if OTP is valid
        if (!$otpRecord) {
            return response()->json(['status' => false, 'message' => 'Invalid or expired OTP']);
        }
    
        // Retrieve or create the user
        $user = User::firstOrCreate(
            [
                'phone' => $otpRecord->phone,
                'email' => $otpRecord->email,
            ],
            [
                'password' => bcrypt('default_password'),
                'name' => $otpRecord->email ? 'User from ' . $otpRecord->email : 'User from ' . $otpRecord->phone, // Fallback name based on email or phone
            ]
        );
    
        // Delete the OTP after successful verification
        $otpRecord->delete();
    
        // Generate Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully',
            'token' => $token,
            'user' => $user,
        ]);
    }

    // public function sendResetLink(Request $request)
    // {
    //     // Validate the email input
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|exists:users,email',
    //     ]);

    //     // Return error response if validation fails
    //     if ($validator->fails()) {
    //         return response()->json(['status' => 'false', 'message' => 'Invalid email address'], 400);
    //     }

    //     // $status = Password::sendResetLink($request->only('email'));
    //     // return $status === Password::RESET_LINK_SENT
    //     //     ? response()->json(['status' => 'true', 'message' => 'Reset link sent to your email'])
    //     //     : response()->json(['status' => 'false', 'message' => 'Failed to send reset link'], 500);
    //     $password=rand(100000, 999999);
    //     $hashedPassword = Hash::make($password);

    //     $user = User::where('email', $request->email)->first();
    //     $user->update(['password' => $hashedPassword]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'OTP generated successfully',
    //         'password' => $password, // Include this for testing purposes only
    //     ]);

    // }

    public function sendResetLink(Request $request)
    {
        // Validate that either email or phone is provided
        $request->validate([
            'email' => 'nullable|email|exists:users,email|required_without:phone',
            'phone' => 'nullable|digits:8|exists:users,phone|required_without:email',
        ]);
    
        // Generate a new random password
        // $password = rand(100000, 999999);
        $password = 123456;
        $hashedPassword = Hash::make($password);
    
        // Find the user by email or phone
        $user = User::where(function ($query) use ($request) {
            if ($request->email) {
                $query->where('email', $request->email);
            }
            // if ($request->phone) {
            //     $query->orWhere('phone', $request->phone);
            // }
        })->first();
    
        // Check if the user exists
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }
    
        // Update the user's password
        $user->update([
            'password' => Hash::make($password),
            // 'company_name' => "test"
        ]);
    
        // Simulate sending the new password (replace with actual email/SMS service)
        if ($request->email) {
            // Replace with your email sending logic
            // Mail::to($user->email)->send(new ResetPasswordMail($password));
        } else {
            // Replace with your SMS sending logic
            // SendOtpService::send($user->phone, $password);
        }
    
        return response()->json([
            'status' => true,
            'message' => 'Password reset successfully. Check your email or phone for the new password.',
            'password' => $password, // Uncomment for testing purposes only
            'user' => $user,
        ]);
    }
    

}
