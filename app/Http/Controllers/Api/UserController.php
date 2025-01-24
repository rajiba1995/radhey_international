<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserAddress;

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric',
            'dob' => 'required|date',
            'company_name' => 'nullable|string|max:255',
            'employee_rank' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'credit_days' => 'nullable|numeric|min:0',
            'gst_number' => 'nullable|string|max:15',
            'billing_address' => 'required|string',
            'billing_landmark' => 'nullable|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_country' => 'required|string|max:255',
            'billing_pin' => 'nullable|string|max:10',
            'shipping_address' => 'nullable|string', // Initially nullable
            'shipping_landmark' => 'nullable|string|max:255', // Initially nullable
            'shipping_city' => 'nullable|string|max:255', // Initially nullable
            'shipping_state' => 'nullable|string|max:255', // Initially nullable
            'shipping_country' => 'nullable|string|max:255', // Initially nullable
            'shipping_pin' => 'nullable|string|max:10', // Initially nullable
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'verified_video' => 'nullable|file|mimes:mp4,avi,mkv|max:10240',
            'gst_certificate_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_billing_shipping_same' => 'required|boolean',
        ]);
        
        // Conditionally make shipping address fields required if is_billing_shipping_same is 0
        if ($request->input('is_billing_shipping_same') == 0) {
            $request->validate([
                'shipping_address' => 'required|string',
                'shipping_landmark' => 'nullable|string|max:255',
                'shipping_city' => 'required|string|max:255',
                'shipping_state' => 'required|string|max:255',
                'shipping_country' => 'required|string|max:255',
                'shipping_pin' => 'nullable|string|max:10',
            ]);
        }
        
    
        try {
            // Handle file uploads and store the relative paths with 'storage/' prefix
            $imagePath = $request->hasFile('profile_image') 
                ? 'storage/' . $request->file('profile_image')->store('profile_images', 'public') 
                : null;
    
            $videoPath = $request->hasFile('verified_video') 
                ? 'storage/' . $request->file('verified_video')->store('verified_videos', 'public') 
                : null;
    
            $gstCertificatePath = $request->hasFile('gst_certificate_image') 
                ? 'storage/' . $request->file('gst_certificate_image')->store('gst_certificates', 'public') 
                : null;
    
            // Create the user with file paths stored in the database
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'dob' => $validated['dob'],
                'company_name' => $validated['company_name'],
                'employee_rank' => $validated['employee_rank'],
                'credit_limit' => $validated['credit_limit'] ?? 0,
                'credit_days' => $validated['credit_days'] ?? 0,
                'gst_number' => $validated['gst_number'],
                'profile_image' => $imagePath,
                'verified_video' => $videoPath,
                'gst_certificate_image' => $gstCertificatePath,
            ]);
    
            // Store billing address
            UserAddress::create([
                'user_id' => $user->id,
                'address_type' => 1, // Billing address
                'address' => $validated['billing_address'],
                'landmark' => $validated['billing_landmark'],
                'city' => $validated['billing_city'],
                'state' => $validated['billing_state'],
                'country' => $validated['billing_country'],
                'pin' => $validated['billing_pin'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            // Store shipping address
            $shippingAddressData = !$validated['is_billing_shipping_same'] 
                ? [
                    'address' => $validated['shipping_address'],
                    'landmark' => $validated['shipping_landmark'],
                    'city' => $validated['shipping_city'],
                    'state' => $validated['shipping_state'],
                    'country' => $validated['shipping_country'],
                    'pin' => $validated['shipping_pin'],
                ]
                : [
                    'address' => $validated['billing_address'],
                    'landmark' => $validated['billing_landmark'],
                    'city' => $validated['billing_city'],
                    'state' => $validated['billing_state'],
                    'country' => $validated['billing_country'],
                    'pin' => $validated['billing_pin'],
                ];
    
            UserAddress::create(array_merge([
                'user_id' => $user->id,
                'address_type' => 2, // Shipping address
                'created_at' => now(),
                'updated_at' => now(),
            ], $shippingAddressData));
    
            // Return the response with the file paths stored in the database
            return response()->json([
                'status' => true,
                'message' => 'Customer information saved successfully!',
                'user' => $user,
                    // 'profile_image' => $imagePath,
                    // 'verified_video' => $videoPath,
                    // 'gst_certificate_image' => $gstCertificatePath,
            ]);
    
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error saving customer information: ' . $e->getMessage());
    
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while saving the customer information.',
            ]);
        }
    }
    public function list(Request $request)
    {
        $users = User::with(['billingAddress','shippingAddress'])  
            ->paginate(10);
        if($users){
            return response()->json([
                'status' => true,
                'message' => 'User list fetched successfully!',
                'data' => $users,
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'No any user list fetched!',
                'data' => $users,
            ]);
        }
       
    }
 
    public function search(Request $request)
    {
        // Fetch query parameters
        $query = User::query();

        // Search by name
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        // Search by company name
        if ($request->filled('company_name')) {
            $query->where('company_name', 'LIKE', '%' . $request->company_name . '%');
        }

        // Search by phone
        if ($request->filled('phone')) {
            $query->where('phone', 'LIKE', '%' . $request->phone . '%');
        }

        // Search by city (billing or shipping)
        // if ($request->filled('city')) {
        //     $query->whereHas('addresses', function ($q) use ($request) {
        //         $q->where('city', 'LIKE', '%' . $request->city . '%');
        //     });
        // }

        // Include related addresses
        $users = $query->paginate(10);

        // Return the response
        if($users){
            return response()->json([
                'status' => true,
                'message' => 'Search results fetched successfully!',
                'data' => $users,
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Sorry, We can not found any result!',
                'data' => $users,
            ]); 
        }
        
    }


}
