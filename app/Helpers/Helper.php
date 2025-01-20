<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Helper
{
    /**
     * Handle image upload and store in a new folder.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $folderName
     * @return string The file path of the uploaded image
     */
    public static function uploadImage($image, $folderName)
    {
        // Ensure the image is valid
        if (!$image->isValid()) {
            throw new \Exception("Invalid image file.");
        }

        $folderPath = 'uploads/' . DIRECTORY_SEPARATOR . $folderName;

        // Generate a unique filename
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
        // Use Laravel's Storage facade for managing file uploads
       

        try {
            // Save the file to the desired location
            $image->storeAs($folderPath, $filename, 'public');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            throw new \Exception("Image could not be moved. Error: " . $e->getMessage());
        }
        // Return the relative path of the uploaded file
        return 'uploads/' . $folderName . '/' . $filename;
    }
    
     public static function generateInvoiceBill()
    {
        $user = Auth::user();

        // Check for salesman type user
        if ($user->user_type == 0 && $user->designation == 2) {
            $salesmanBillBook = SalesmanBilling::where('salesman_id', $user->id)
                ->whereColumn('total_count', '>', 'no_of_used')
                ->first();

            if ($salesmanBillBook) {
                // If a valid SalesmanBilling record is found
                $data = [
                    'number' => $salesmanBillBook->start_no + $salesmanBillBook->no_of_used,
                    'status' => 1,
                ];
                return $data;
            } else {
                // If no SalesmanBilling record is found
                $data = [
                    'number' => '0000',
                    'status' => 1,
                ];
                return $data;
            }
        }

        // Check for other user type (e.g., managers)
        if ($user->user_type == 0 && $user->designation == 1) {
            $data = [
                'number' => 0,
                'status' => 0,
            ];
            return $data;
        }
    }
}
