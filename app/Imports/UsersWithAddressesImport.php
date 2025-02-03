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
    //     // Find or create the user
    //     $user = User::updateOrCreate(
    //         ['email' => $row['email']], // Unique identifier
    //         [
    //             'name' => $row['name'],
    //             'phone' => $row['phone'],
    //             'company_name' => $row['company_name'],
    //             'designation' => $row['designation'],
    //         ]
    //     );

    //     // Insert or update the address for the user
    //     UserAddress::updateOrCreate(
    //         ['user_id' => $user->id, 'address_type' => $row['address_type']],
    //         [
    //             'address' => $row['address'],
    //             'city' => $row['city'],
    //             'state' => $row['state'],
    //             'country' => $row['country'],
    //             'zip_code' => $row['zip_code'],
    //         ]
    //     );

    //     return $user;
    // }
    public function model(array $row)
    {
        // Convert user type
        $userType = strtolower($row['user_type']) == 'staff' ? 0 : 1;

        // Get branch ID from branch name
        // $branch = Branch::where('name', $row['branch'])->first();
        // $branchId = $branch ? $branch->id : null;

        // Get country ID from country title
        // $country = Country::where('title', $row['country'])->first();
        // $countryId = $country ? $country->id : null;

        // Find existing user by email or create a new one
        $user = User::updateOrCreate(
            ['email' => $row['email']], // Check if email exists
            [
                'name'         => $row['user_name'],
                'phone'        => $row['phone'],
                'company_name' => $row['company_name'],
                // 'branch_id'    => $branchId,
                // 'country_id'   => $countryId,
                'user_type'    => $userType,
                'password'     => Hash::make('default123'), // Set a default password (can be changed later)
            ]
        );

        // Convert address type
        $addressType = strtolower($row['address_type']) == 'billing address' ? 1 : 2;

        // Insert or update user address
        UserAddress::updateOrCreate(
            ['user_id' => $user->id, 'address_type' => $addressType], // Check existing address
            [
                'address'  => $row['address'],
                'landmark' => $row['landmark'],
                'city'     => $row['city'],
                'state'    => $row['state'],
                'country' => $row['country'],
                'zip_code' => $row['zip_code'],
            ]
        );

        return $user;
    }
}

