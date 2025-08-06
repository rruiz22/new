<?php

if (!function_exists('get_ffmpeg_path')) {
    /**
     * Get FFmpeg path based on environment and OS
     * 
     * @return string FFmpeg executable path
     */
    function get_ffmpeg_path()
    {
        // Try to get from database configuration first
        $integrationModel = new \App\Models\IntegrationModel();
        $ffmpegConfig = $integrationModel->getServiceConfiguration('ffmpeg');
        
        if (!empty($ffmpegConfig) && 
            isset($ffmpegConfig['ffmpeg_path']['value']) && 
            !empty($ffmpegConfig['ffmpeg_path']['value']) &&
            $ffmpegConfig['ffmpeg_path']['value'] !== 'ffmpeg') {
            return $ffmpegConfig['ffmpeg_path']['value'];
        }
        
        // Auto-detect based on environment and OS
        return detect_ffmpeg_path();
    }
}

if (!function_exists('detect_ffmpeg_path')) {
    /**
     * Auto-detect FFmpeg path based on OS and environment
     * 
     * @return string FFmpeg executable path
     */
    function detect_ffmpeg_path()
    {
        $isWindows = (PHP_OS_FAMILY === 'Windows');
        $isProduction = (ENVIRONMENT === 'production');
        
        if ($isWindows) {
            // Development on Windows
            $paths = [
                'C:\\ffmpeg\\ffmpeg-7.1.1-essentials_build\\bin\\ffmpeg.exe',
                'C:\\ffmpeg\\bin\\ffmpeg.exe',
                'ffmpeg' // Try PATH
            ];
        } else {
            // Linux/Unix (usually production)
            $paths = [
                '/usr/bin/ffmpeg',           // Ubuntu/Debian default
                '/usr/local/bin/ffmpeg',     // Compiled from source
                '/opt/ffmpeg/bin/ffmpeg',    // Custom installation
                'ffmpeg'                     // Try PATH
            ];
        }
        
        // Test each path and return the first working one
        foreach ($paths as $path) {
            if (test_ffmpeg_path($path)) {
                return $path;
            }
        }
        
        // Fallback
        return $isWindows ? 'ffmpeg' : '/usr/bin/ffmpeg';
    }
}

if (!function_exists('test_ffmpeg_path')) {
    /**
     * Test if FFmpeg path is valid and working
     * 
     * @param string $path FFmpeg executable path
     * @return bool True if FFmpeg is working
     */
    function test_ffmpeg_path($path)
    {
        try {
            $output = [];
            $returnCode = null;
            
            // Suppress errors and test
            exec($path . ' -version 2>&1', $output, $returnCode);
            
            return ($returnCode === 0 && !empty($output));
        } catch (Exception $e) {
            return false;
        }
    }
}

if (!function_exists('get_ffmpeg_info')) {
    /**
     * Get FFmpeg version and capabilities info
     * 
     * @return array FFmpeg information
     */
    function get_ffmpeg_info()
    {
        $ffmpegPath = get_ffmpeg_path();
        $output = [];
        $returnCode = null;
        
        exec($ffmpegPath . ' -version 2>&1', $output, $returnCode);
        
        if ($returnCode !== 0 || empty($output)) {
            return [
                'installed' => false,
                'version' => 'Not found',
                'path' => $ffmpegPath,
                'codecs' => []
            ];
        }
        
        // Parse version
        $version = 'Unknown';
        $codecs = [];
        
        foreach ($output as $line) {
            if (strpos($line, 'ffmpeg version') === 0) {
                preg_match('/ffmpeg version (\S+)/', $line, $matches);
                $version = $matches[1] ?? 'Unknown';
            }
            if (strpos($line, 'configuration:') === 0) {
                // Check for common codecs
                if (strpos($line, '--enable-libx264') !== false) {
                    $codecs[] = 'H.264';
                }
                if (strpos($line, '--enable-libx265') !== false) {
                    $codecs[] = 'H.265';
                }
                if (strpos($line, '--enable-libvpx') !== false) {
                    $codecs[] = 'VP8/VP9';
                }
            }
        }
        
        return [
            'installed' => true,
            'version' => $version,
            'path' => $ffmpegPath,
            'codecs' => $codecs,
            'environment' => ENVIRONMENT,
            'os' => PHP_OS_FAMILY
        ];
    }
}

if (!function_exists('process_video')) {
    /**
     * Process video using FFmpeg
     * 
     * @param string $inputPath Input video path
     * @param string $outputPath Output video path  
     * @param array $options Processing options
     * @return array Processing result
     */
    function process_video($inputPath, $outputPath, $options = [])
    {
        $ffmpegPath = get_ffmpeg_path();
        
        // Default options
        $defaults = [
            'quality' => 'medium',
            'format' => 'mp4',
            'resolution' => '1080p',
            'generate_thumbnail' => true,
            'thumbnail_time' => 5
        ];
        
        $options = array_merge($defaults, $options);
        
        // Build FFmpeg command
        $command = build_ffmpeg_command($ffmpegPath, $inputPath, $outputPath, $options);
        
        // Execute command
        $output = [];
        $returnCode = null;
        exec($command, $output, $returnCode);
        
        return [
            'success' => ($returnCode === 0),
            'command' => $command,
            'output' => $output,
            'return_code' => $returnCode
        ];
    }
}

if (!function_exists('build_ffmpeg_command')) {
    /**
     * Build FFmpeg command string
     * 
     * @param string $ffmpegPath FFmpeg executable path
     * @param string $inputPath Input file path
     * @param string $outputPath Output file path
     * @param array $options Processing options
     * @return string FFmpeg command
     */
    function build_ffmpeg_command($ffmpegPath, $inputPath, $outputPath, $options)
    {
        $cmd = escapeshellarg($ffmpegPath);
        $cmd .= ' -i ' . escapeshellarg($inputPath);
        
        // Video codec and quality
        switch ($options['quality']) {
            case 'high':
                $cmd .= ' -c:v libx264 -crf 18 -preset slow';
                break;
            case 'low':
                $cmd .= ' -c:v libx264 -crf 28 -preset fast';
                break;
            default: // medium
                $cmd .= ' -c:v libx264 -crf 23 -preset medium';
        }
        
        // Audio codec
        $cmd .= ' -c:a aac -b:a 128k';
        
        // Resolution
        if ($options['resolution'] !== 'original') {
            switch ($options['resolution']) {
                case '720p':
                    $cmd .= ' -vf scale=-2:720';
                    break;
                case '1440p':
                    $cmd .= ' -vf scale=-2:1440';
                    break;
                case '2160p':
                    $cmd .= ' -vf scale=-2:2160';
                    break;
                default: // 1080p
                    $cmd .= ' -vf scale=-2:1080';
            }
        }
        
        $cmd .= ' ' . escapeshellarg($outputPath);
        
        return $cmd;
    }
}