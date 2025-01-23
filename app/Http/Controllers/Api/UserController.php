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
       
        $user = User::find(auth()->id());
        if (!$user) {
            return response()->json(['status' => false,'message' => 'No data found']);
        }
    
    
    
        return response()->json([
            'status' => true,
            'message' => 'User details',
            'data' => $user, // Include this for testing purposes only
        ]);
    }
}
