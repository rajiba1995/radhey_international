<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

       // Generate folder path in the public directory
        $folderPath = public_path('uploads' . DIRECTORY_SEPARATOR . $folderName);

        // Create folder if it doesn't exist
        if (!File::exists($folderPath)) {
            // This will create the folder with proper permissions if it doesn't exist
            File::makeDirectory($folderPath, 0755, true);
        }

        // Generate a unique filename
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
        $image->move($folderPath, $filename);
        // Return the relative path of the uploaded file
        return 'uploads/' . $folderName . '/' . $filename;
    }
}
