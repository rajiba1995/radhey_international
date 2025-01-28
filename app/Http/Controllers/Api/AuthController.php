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
        try {
            // Validate the incoming request
            $request->validate([
                'phone' => 'nullable|required_without:email',
                'email' => 'nullable|email|required_without:phone',
                'password' => 'required',
            ]);
    
            $user = null;
    
            // Find the user based on email or phone
            if ($request->email) {
                $user = User::where('email', $request->email)->first();
            } elseif ($request->phone) {
                $user = User::where('phone', $request->phone)->first();
            }
    
            // If no user is found, return an error response
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found with provided email or phone',
                ]);
            }
    
            // Check if the provided password is correct
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid password',
                ]);
            }
    
            // Generate OTP and expiration time
            $otp = rand(100000, 999999);
            $expiresAt = now()->addMinutes(5);
    
            // Store the OTP in the database
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
    
            // Set OTP verification flag for the user
            $user->otp_verification = 1;  // OTP not verified
            $user->save();
    
            // Return success response with OTP
            return response()->json([
                'status' => true,
                'message' => 'OTP generated successfully',
                'otp' => $otp, 
            ]);
    
        } catch (\Exception $e) {
            // Log the exception message for debugging purposes
            Log::error('OTP generation error: ' . $e->getMessage());
    
            // Return an error response
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while generating the OTP',
            ]);
        }
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
        try {
            // Validate the incoming request
            $request->validate([
                'otp' => 'required|digits:6',
                'email' => 'nullable|email|exists:users,email|required_without:phone',
                'phone' => 'nullable|exists:users,phone|required_without:email',
            ]);
    
            // Retrieve the OTP record from the database
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
    
            // Retrieve the user based on email or phone from OTP record
            $user = null;
            if ($otpRecord->email) {
                $user = User::where('email', $otpRecord->email)->first();
            } elseif ($request->phone) {
                $user = User::where('phone', $otpRecord->phone)->first();
            }
    
            // Update OTP verification status and save IP address
            $user->otp_verification = 2;  // OTP verified
            $user->ip_address = request()->userAgent();
            $user->save();
    
            // Delete the OTP record after successful verification
            $otpRecord->delete();
    
            // Optionally generate a Sanctum token for authentication
            // $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully',
                'user' => $user,
            ]);
    
        } catch (\Exception $e) {
            // Log the exception message for debugging purposes
            Log::error('OTP verification error: ' . $e->getMessage());
    
            // Return a generic error response
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during OTP verification',
            ]);
        }
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
        try {
            // Validate that either email or phone is provided
            $request->validate([
                'email' => 'nullable|email|exists:users,email|required_without:phone',
                'phone' => 'nullable|exists:users,phone|required_without:email',
            ]);

            // Generate a random 6-digit password
            $password = Str::random(6);

            // Retrieve the user based on email or phone
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
            $user->update(['password' => Hash::make($password)]); // Ensure password is hashed

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
                'password' => $password, // For testing purposes only, remove in production
            ]);
        
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Password reset error: ' . $e->getMessage());

            // Return a generic error response
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while resetting the password. Please try again later.',
            ], 500);
        }
    }




    // public function loginWithMpin(Request $request)
    // {
    //     // Validate MPIN
    //     $request->validate([
    //         'mpin' => 'required|numeric',
    //         'email' => 'nullable|email|exists:users,email|required_without:phone',
    //         'phone' => 'nullable|exists:users,phone|required_without:email',
    //     ]);
    
    //     // Get the IP address of the user
    //     $ip_address = request()->userAgent();
    
    //     // Find user by email or phone and IP address
    //     $user = User::where(function ($query) use ($request, $ip_address) {
    //         if ($request->email) {
    //             $query->where('email', $request->email)->where('ip_address', $ip_address);
    //         }
    //         if ($request->phone) {
    //             $query->where('phone', $request->phone)->where('ip_address', $ip_address);
    //         }
    //     })->first();
    
    //     // If user not found, return error response
    //     if (!$user) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'User not found with the provided details and IP address',
    //         ]);
    //     }
    
    //     // Check if MPIN exists; if not, save the provided MPIN
    //     if (!$user->mpin) {
    //         $user->mpin = $request->mpin;
    //         $user->save();
    //     } else {
    //         // Validate the MPIN
    //         $user = User::where('mpin', $request->mpin)->where('ip_address', $ip_address)->first();
    
    //         if (!$user) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Invalid MPIN',
    //             ]);
    //         }
    //     }
    
    //     // Generate Sanctum token
    //     $token = $user->createToken('MPIN-Login')->plainTextToken;
    
    //     // Set token expiration to 20 seconds
    //     $user->tokens()->latest('created_at')->first()->update([
    //         // 'expires_at' => now()->addSeconds(20),
    //         'expires_at' => now()->addHours(8),
            
    //     ]);
    
    //     // Return response with token
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Logged in successfully',
    //         'token' => $token,
    //     ]);
    // }
    
    public function loginWithMpin(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'mpin' => 'required|numeric',
                'email' => 'nullable|email|exists:users,email|required_without:phone',
                'phone' => 'nullable|exists:users,phone|required_without:email',
            ]);
        
            // Retrieve user's IP address
            $ip_address = request()->userAgent();
            
            // Find user by email/phone and IP address
            $user = User::where('ip_address', $ip_address)
                ->when($request->email, fn($query) => $query->where('email', $request->email))
                ->when($request->phone, fn($query) => $query->where('phone', $request->phone))
                ->first();
        
            // If user not found, return error
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found with the provided details and IP address',
                ]);
            }
        
            // Handle MPIN logic
            if (!$user->mpin) {
                $user->mpin = $request->mpin;
                $user->save();
            } elseif ($user->mpin !== $request->mpin) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid MPIN',
                ]);
            }
        
            // Generate Sanctum token
            $token = $user->createToken('MPIN-Login')->plainTextToken;
        
            // Update token expiration (requires `expires_at` column in `personal_access_tokens`)
            $user->tokens()->latest('created_at')->first()->update([
                'expires_at' => now()->addHours(8),
            ]);
        
            // Return success response
            return response()->json([
                'status' => true,
                'message' => 'Logged in successfully',
                'token' => $token,
            ]);
        
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Login with MPIN error: ' . $e->getMessage());

            // Return a generic error response
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during login. Please try again later.',
            ], 500);
        }
    }

    
    

}
