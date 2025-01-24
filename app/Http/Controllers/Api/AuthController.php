<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function generateOtp(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|required_without:email',
            'email' => 'nullable|email|required_without:phone',
            'password' => 'required',
        ]);
    
        $user = null;
        if ($request->email) {
            $user = User::where('email', $request->email)->first();
        } elseif ($request->phone) {
            $user = User::where('phone', $request->phone)->first();
        }
    
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found with provided email or phone',
            ]);
        }
        if (!Hash::check($request->password , $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid password',
            ]);
        }
    
        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);
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
        $user->otp_verification = 1;  // OTP not verified
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'OTP generated successfully',
            'otp' => $otp, 
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
            'otp' => 'required|digits:6',
            'email' => 'nullable|email|exists:users,email|required_without:phone',
            'phone' => 'nullable|exists:users,phone|required_without:email',
        ]);
   

    $otpRecord = Otp::where(function ($query) use ($request) {
        if ($request->email) {
            $query->where('email', $request->email);
        }
        if ($request->phone) {
            $query->orWhere('phone', $request->phone);
        }
    })->where('otp', $request->otp)
            ->where('expires_at', '>=', now())
            ->first();
    
        // Check if OTP is valid
        if (!$otpRecord) {
            return response()->json(['status' => false, 'message' => 'Invalid or expired OTP']);
        }

        $user = null;
        if ($otpRecord->email) {
            $user = User::where('email', $otpRecord->email)->first();
            // dd( $user);
        } elseif ($request->phone) {
            $user = User::where('phone', $otpRecord->phone)->first();
        }
        $user->otp_verification = 2;  // OTP not verified
        $user->ip_address = request()->userAgent();
        $user->save();
        // Delete the OTP after successful verification
        $otpRecord->delete();
    
        // Generate Sanctum token
        // $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully',
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
            'phone' => 'nullable|exists:users,phone|required_without:email',
        ]);
       
        $password = Str::random(6);; // Generate a random 6-digit password
    
        $user = User::where(function ($query) use ($request) {
            if ($request->email) {
                $query->where('email', $request->email);
            }
            if ($request->phone) {
                $query->orWhere('phone', $request->phone);
            }
        })->first();
    
        // Check if the user exists
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }
    
        // Update the user's password
        $user->update(['password' => Hash::make($request->new_password)]);
        // $user->update(['password' => $request->new_password]);

    
        // Simulate sending the new password (replace with actual email/SMS service)
        if ($request->email) {
            // Replace with your email sending logic
            // Mail::to($user->email)->send(new ResetPasswordMail($password));
        } elseif ($request->phone) {
            // Replace with your SMS sending logic
            // SendOtpService::send($user->phone, "Your new password is: $password");
        }
    
        return response()->json([
            'status' => true,
            'message' => 'Password reset successfully. Check your email or phone for the new password.',
            'password' => $user->password, // For testing purposes only, remove in production
            'user'=> $user
        ]);
    }



    public function loginWithNpin(Request $request)
    {
        // Validate NPIN
        $request->validate([
            'npin' => 'required|numeric',
            'email' => 'nullable|email|exists:users,email|required_without:phone',
            'phone' => 'nullable|exists:users,phone|required_without:email',
        ]);
    
        // Get the IP address of the user
        $ip_address = request()->userAgent();
    
        // Find user by email or phone and IP address
        $user = User::where(function ($query) use ($request, $ip_address) {
            if ($request->email) {
                $query->where('email', $request->email)->where('ip_address', $ip_address);
            }
            if ($request->phone) {
                $query->where('phone', $request->phone)->where('ip_address', $ip_address);
            }
        })->first();
    
        // If user not found, return error response
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found with the provided details and IP address',
            ]);
        }
    
        // Check if NPIN exists; if not, save the provided NPIN
        if (!$user->npin) {
            $user->npin = $request->npin;
            $user->save();
        } else {
            // Validate the NPIN
            $user = User::where('npin', $request->npin)->where('ip_address', $ip_address)->first();
    
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid NPIN',
                ]);
            }
        }
    
        // Generate Sanctum token
        $token = $user->createToken('NPIN-Login')->plainTextToken;
    
        // Set token expiration to 20 seconds
        $user->tokens()->latest('created_at')->first()->update([
            // 'expires_at' => now()->addSeconds(20),
            'expires_at' => now()->addHours(8),
            
        ]);
    
        // Return response with token
        return response()->json([
            'status' => true,
            'message' => 'Logged in successfully',
            'token' => $token,
        ]);
    }
    

    

}
