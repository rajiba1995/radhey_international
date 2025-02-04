<?php

namespace App\Imports;

use App\Models\User;
// use App\Models\UserAddress;
// use App\Models\User;
use App\Models\UserAddress;
// use App\Models\Branch;
// use App\Models\Country;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersWithAddressesImport implements ToModel, WithHeadingRow
{
    
    // public function model(array $row)
    // {
      
    //     $userType = strtolower($row['user_type']) == 'staff' ? 0 : 1;
    //     $user = User::updateOrCreate(
    //         ['email' => $row['email']], // Check if email exists
    //         [
    //             'name'         => $row['user_name'],
    //             'phone'        => $row['phone'],
    //             'company_name' => $row['company_name'],
    //             'user_type'    => $userType,
    //             'password'     => Hash::make('default123'), // Set a default password (can be changed later)
    //         ]
    //     );

    //     $addressType = strtolower($row['address_type']) == 'billing address' ? 1 : 2;
    //     // Insert or update user address
    //     UserAddress::updateOrCreate(
    //         ['user_id' => $user->id, 'address_type' => $addressType], // Check existing address
    //         [
    //             'address'  => $row['address'],
    //             'landmark' => $row['landmark'],
    //             'city'     => $row['city'],
    //             'state'    => $row['state'],
    //             'country' => $row['country'],
    //             'zip_code' => $row['zip_code'],
    //         ]
    //     );

    //     return $user;
    // }

    // public function model(array $row)
    // {
    //     $userType = strtolower(trim($row['user_type'])) == 'staff' ? 0 : 1;

    //     $user = User::updateOrCreate(
    //         ['email' => trim($row['email'])], // Check if email exists
    //         [
    //             'name'          => trim($row['user_name']),
    //             'phone'         => trim($row['phone']),
    //             'company_name'  => trim($row['company_name']),
    //             'employee_rank' => trim($row['rank'] ?? null), // Handle nullable rank
    //             'whatsapp_no'   => trim($row['whatsapp_number'] ?? null), // Handle nullable WhatsApp
    //             'dob'           => isset($row['dob']) ? date('Y-m-d', strtotime($row['dob'])) : null, // Ensure valid date format
    //             'profile_image' => isset($row['profile_image']) ? $this->saveFile($row['profile_image']) : null, // Handle file saving
    //             'verified_video' => isset($row['verified_video']) ? $this->saveFile($row['verified_video']) : null, // Handle file saving
    //             'user_type'     => $userType,
    //             'password'      => Hash::make('default123'), // Set a default password (can be changed later)
    //             'status'        => strtolower(trim($row['status'])) == 'active' ? 1 : 0, // Convert status to boolean
    //         ]
    //     );

    //     // Convert address type
    //     $addressType = strtolower(trim($row['address_type'])) == 'billing address' ? 1 : 2;

    //     // Insert or update user address
    //     UserAddress::updateOrCreate(
    //         ['user_id' => $user->id, 'address_type' => $addressType], // Check existing address
    //         [
    //             'address'  => trim($row['address']),
    //             'landmark' => trim($row['landmark']),
    //             'city'     => trim($row['city']),
    //             'state'    => trim($row['state']),
    //             'country'  => trim($row['country']),
    //             'zip_code' => trim($row['zip_code']),
    //         ]
    //     );

    //     return $user;
    // }

    // /**
    //  * Handle file saving from URLs
    //  */
    // private function saveFile($fileUrl)
    // {
    //     try {
    //         if (!filter_var($fileUrl, FILTER_VALIDATE_URL)) {
    //             return null;
    //         }

    //         $fileContents = file_get_contents($fileUrl);
    //         if (!$fileContents) {
    //             return null;
    //         }

    //         $fileName = 'uploads/' . uniqid() . '.' . pathinfo($fileUrl, PATHINFO_EXTENSION);
    //         Storage::disk('public')->put($fileName, $fileContents);

    //         return $fileName;
    //     } catch (\Exception $e) {
    //         return null;
    //     }
    // }

    public function model(array $row)
    {
        $userType = strtolower(trim($row['user_type'])) == 'staff' ? 0 : 1;
        // Create or update user record
        $user = User::updateOrCreate(
            ['email' => $row['email']], // Unique identifier
            [
                'emergency_contact_person' => $row['emergency_contact_person'] ?? null,
                'emergency_mobile' => $row['emergency_mobile'] ?? null,
                'emergency_whatsapp' => $row['emergency_whatsapp'] ?? null,
                'emergency_address' => $row['emergency_address'] ?? null,
                // 'branch_id' => $row['branch_id'] ?? null,
                'employee_id' => $row['employee_id'] ?? null,
                // 'country_id' => $row['country_id'] ?? null,
                'name' => $row['user_name'],
                'dob' => $row['dob'] ?? null,
                'user_type' => $userType,
                // 'designation' => $row['designation'] ?? null,
                'company_name' => $row['company_name'] ?? null,
                'employee_rank' => $row['employee_rank'] ?? null,
                'phone' => $row['phone'] ?? null,
                'whatsapp_no' => $row['whatsapp_no'] ?? null,
                'image' => $row['image'] ?? null,
                'profile_image' => $row['profile_image'] ?? null,
                'verified_video' => $row['verified_video'] ?? null,
                'password' => bcrypt($row['password'] ?? 'defaultpassword'),
            ]
        );

        // Store or update user address (billing)
        if (!empty($row['billing_address'])) {
            UserAddress::updateOrCreate(
                ['user_id' => $user->id, 'address_type' => 1], // 1 = Billing
                [
                    'address' => $row['billing_address'],
                    'landmark' => $row['billing_landmark'] ?? null,
                    'city' => $row['billing_city'] ?? null,
                    'state' => $row['billing_state'] ?? null,
                    'country' => $row['billing_country'] ?? null,
                    'zip_code' => $row['billing_zip'] ?? null,
                ]
            );
        }

        // Store or update user address (shipping)
        if (!empty($row['shipping_address'])) {
            UserAddress::updateOrCreate(
                ['user_id' => $user->id, 'address_type' => 2], // 2 = Shipping
                [
                    'address' => $row['shipping_address'],
                    'landmark' => $row['shipping_landmark'] ?? null,
                    'city' => $row['shipping_city'] ?? null,
                    'state' => $row['shipping_state'] ?? null,
                    'country' => $row['shipping_country'] ?? null,
                    'zip_code' => $row['shipping_zip'] ?? null,
                ]
            );
        }

        return $user;
    }
}

