<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
       
        $user = User::first();
        if (!$user) {
            return response()->json(['status' => false,'message' => 'Invalid phone or password']);
        }
    
        // Generate OTP
       
    
        // Store OTP in the database
        
    
        // Simulate sending OTP (replace with an actual SMS API)
        // SendOtpService::send($request->phone, $otp);
    
        return response()->json([
            'status' => true,
            'message' => 'OTP generated successfully',
            'otp' => $user, // Include this for testing purposes only
        ]);
    }
}
