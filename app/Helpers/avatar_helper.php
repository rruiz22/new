<?php

if (!function_exists('getAvatarUrl')) {
    /**
     * Get avatar URL with intelligent fallback system
     * 
     * @param object|null $user User object
     * @param int $size Avatar size in pixels (default: 150)
     * @param string $style Avatar style for generated avatars
     * @return string Avatar URL
     */
    function getAvatarUrl($user = null, $size = 150, $style = 'initials')
    {
        // If no user provided, get current user
        if (!$user) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return getDefaultAvatar($size, $style);
        }
        
        // 1. Check for uploaded avatar first
        if (!empty($user->avatar) && file_exists(FCPATH . 'assets/images/users/' . $user->avatar)) {
            return base_url('assets/images/users/' . $user->avatar);
        }
        
        // 2. Skip Gravatar to avoid privacy tracking warnings
        // Generate avatar based on style preference instead
        switch ($style) {
            case 'initials':
                return getInitialsAvatar($user, $size);
            case 'robohash':
                return getRobohashAvatar($user, $size);
            case 'identicon':
                return getIdenticonAvatar($user, $size);
            default:
                return getInitialsAvatar($user, $size);
        }
    }
}

if (!function_exists('getGravatarUrl')) {
    /**
     * Get Gravatar URL
     * 
     * @param string $email User email
     * @param int $size Size in pixels
     * @param string $default Default image type
     * @return string Gravatar URL
     */
    function getGravatarUrl($email, $size = 150, $default = '404')
    {
        $hash = md5(strtolower(trim($email)));
        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d={$default}";
    }
}

if (!function_exists('isGravatarAvailable')) {
    /**
     * Check if Gravatar exists for email
     * 
     * @param string $email User email
     * @return bool True if Gravatar exists
     */
    function isGravatarAvailable($email)
    {
        $hash = md5(strtolower(trim($email)));
        $url = "https://www.gravatar.com/avatar/{$hash}?d=404";
        
        // Use cURL to check if Gravatar exists
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode === 200;
    }
}

if (!function_exists('getInitialsAvatar')) {
    /**
     * Generate initials-based avatar using UI Avatars
     * 
     * @param object $user User object
     * @param int $size Size in pixels
     * @return string Avatar URL
     */
    function getInitialsAvatar($user, $size = 150)
    {
        // Get user initials
        $initials = '';
        
        // Try to get from first_name and last_name
        if (!empty($user->first_name) && !empty($user->last_name)) {
            $initials = strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1));
        }
        // Try to get from username
        elseif (!empty($user->username)) {
            $parts = explode('_', $user->username);
            if (count($parts) >= 2) {
                $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
            } else {
                $initials = strtoupper(substr($user->username, 0, 2));
            }
        }
        // Fallback to email
        elseif (!empty($user->email)) {
            $initials = strtoupper(substr($user->email, 0, 2));
        } else {
            $initials = 'U';
        }
        
        // Generate colors based on user ID for consistency
        $colors = [
            '3498db', '9b59b6', 'e74c3c', 'f39c12', 
            '2ecc71', '1abc9c', 'e67e22', 'f1c40f',
            '8e44ad', 'c0392b', 'd35400', '27ae60'
        ];
        $colorIndex = ($user->id ?? 1) % count($colors);
        $backgroundColor = $colors[$colorIndex];
        
        // Use UI Avatars service
        $name = urlencode($initials);
        return "https://ui-avatars.com/api/?name={$name}&size={$size}&background={$backgroundColor}&color=ffffff&bold=true&format=png";
    }
}

if (!function_exists('getRobohashAvatar')) {
    /**
     * Generate robohash avatar
     * 
     * @param object $user User object
     * @param int $size Size in pixels
     * @return string Avatar URL
     */
    function getRobohashAvatar($user, $size = 150)
    {
        $seed = $user->email ?? $user->username ?? $user->id ?? 'default';
        return "https://robohash.org/{$seed}?size={$size}x{$size}&set=set1";
    }
}

if (!function_exists('getIdenticonAvatar')) {
    /**
     * Generate identicon avatar using DiceBear API instead of Gravatar
     * 
     * @param object $user User object
     * @param int $size Size in pixels
     * @return string Avatar URL
     */
    function getIdenticonAvatar($user, $size = 150)
    {
        $seed = $user->email ?? $user->username ?? $user->id ?? 'default';
        // Use DiceBear API for identicons instead of Gravatar
        return "https://api.dicebear.com/6.x/identicon/svg?seed=" . urlencode($seed) . "&size={$size}";
    }
}

if (!function_exists('getDefaultAvatar')) {
    /**
     * Get default avatar
     * 
     * @param int $size Size in pixels
     * @param string $style Style preference
     * @return string Avatar URL
     */
    function getDefaultAvatar($size = 150, $style = 'initials')
    {
        switch ($style) {
            case 'robohash':
                return "https://robohash.org/default?size={$size}x{$size}&set=set1";
            case 'identicon':
                return "https://api.dicebear.com/6.x/identicon/svg?seed=default&size={$size}";
            default:
                return "https://ui-avatars.com/api/?name=U&size={$size}&background=6c757d&color=ffffff&bold=true&format=png";
        }
    }
}

if (!function_exists('uploadAvatar')) {
    /**
     * Upload and process avatar image
     * 
     * @param mixed $file Uploaded file
     * @param int $userId User ID
     * @param int $maxSize Max file size in KB (default: 2MB)
     * @return array Result with success/error
     */
    function uploadAvatar($file, $userId, $maxSize = 2048)
    {
        if (!$file || !$file->isValid()) {
            return ['success' => false, 'error' => 'Invalid file'];
        }
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return ['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.'];
        }
        
        // Validate file size
        if ($file->getSize() > ($maxSize * 1024)) {
            return ['success' => false, 'error' => "File too large. Maximum size is {$maxSize}KB."];
        }
        
        // Create upload directory if it doesn't exist
        $uploadPath = FCPATH . 'assets/images/users/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Generate unique filename
        $extension = $file->getClientExtension();
        $filename = $userId . '_' . time() . '.' . $extension;
        
        try {
            // Move file
            $file->move($uploadPath, $filename);
            
            // Resize image if needed
            $imagePath = $uploadPath . $filename;
            if (resizeImage($imagePath, 300, 300)) {
                return ['success' => true, 'filename' => $filename];
            } else {
                return ['success' => true, 'filename' => $filename, 'warning' => 'Image uploaded but could not be resized'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Failed to upload file: ' . $e->getMessage()];
        }
    }
}

if (!function_exists('resizeImage')) {
    /**
     * Resize image to specified dimensions
     * 
     * @param string $imagePath Path to image
     * @param int $width Target width
     * @param int $height Target height
     * @return bool Success status
     */
    function resizeImage($imagePath, $width = 300, $height = 300)
    {
        if (!extension_loaded('gd')) {
            return false;
        }
        
        $imageInfo = getimagesize($imagePath);
        if (!$imageInfo) {
            return false;
        }
        
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $imageType = $imageInfo[2];
        
        // Only resize if image is larger than target
        if ($originalWidth <= $width && $originalHeight <= $height) {
            return true;
        }
        
        // Calculate proportional dimensions
        $ratio = min($width / $originalWidth, $height / $originalHeight);
        $newWidth = (int)($originalWidth * $ratio);
        $newHeight = (int)($originalHeight * $ratio);
        
        // Create image resource
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($imagePath);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($imagePath);
                break;
            default:
                return false;
        }
        
        if (!$source) {
            return false;
        }
        
        // Create new image
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and GIF
        if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }
        
        // Resize
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        
        // Save
        $result = false;
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $result = imagejpeg($resized, $imagePath, 85);
                break;
            case IMAGETYPE_PNG:
                $result = imagepng($resized, $imagePath, 6);
                break;
            case IMAGETYPE_GIF:
                $result = imagegif($resized, $imagePath);
                break;
        }
        
        // Clean up
        imagedestroy($source);
        imagedestroy($resized);
        
        return $result;
    }
} 