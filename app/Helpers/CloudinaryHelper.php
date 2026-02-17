<?php

namespace App\Helpers;

class CloudinaryHelper
{
    /**
     * Convert a Cloudinary relative path to a full CDN URL
     * 
     * @param string $path - Relative path from Cloudinary (e.g., 'products/test_123.jpg')
     * @return string - Full Cloudinary CDN URL
     */
    public static function getUrl(string $path): string
    {
        // Get from config which handles both env and cached config
        $cloudinaryUrl = config('filesystems.disks.cloudinary.url');
        
        if (empty($cloudinaryUrl)) {
            return $path;
        }
        
        // Extract cloud name from CLOUDINARY_URL
        // Format: cloudinary://API_KEY:API_SECRET@CLOUD_NAME
        if (preg_match('/@([a-z0-9]+)$/', $cloudinaryUrl, $matches)) {
            $cloudName = $matches[1];
            return 'https://res.cloudinary.com/' . $cloudName . '/image/upload/' . $path;
        }
        
        // Fallback if regex doesn't match
        return $path;
    }
}
