<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Video Processing Configuration<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>Video Processing<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.manage_integrations') ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.video-container {
    background: #f8f9fa;
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

.video-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #e9ecef;
}

.video-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.video-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #6c5ce7, #a29bfe);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.video-icon i {
    color: #fff;
    font-size: 1.5rem;
}

.nav-back {
    display: inline-flex;
    align-items: center;
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.nav-back:hover {
    color: #0056b3;
    text-decoration: none;
}

.nav-back i {
    margin-right: 0.5rem;
}

.ffmpeg-status {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.ffmpeg-status.installed {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.ffmpeg-status.not-installed {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.format-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 0.5rem;
    margin: 1rem 0;
}

.format-badge {
    background: #e9ecef;
    padding: 0.5rem;
    border-radius: 6px;
    text-align: center;
    font-size: 0.875rem;
    font-weight: 500;
}

.format-badge.supported {
    background: #d4edda;
    color: #155724;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="video-container">
    <div class="container-fluid">
        <a href="<?= base_url('integrations') ?>" class="nav-back">
            <i class="ri-arrow-left-line"></i>
            <?= lang('App.manage_integrations') ?>
        </a>

        <div class="row">
            <div class="col-lg-8">
                <!-- FFmpeg Configuration -->
                <div class="video-card">
                    <div class="video-header">
                        <div class="video-icon">
                            <i class="ri-video-line"></i>
                        </div>
                        <div>
                            <h4>FFmpeg Configuration</h4>
                            <p class="text-muted mb-0">Video processing and optimization settings</p>
                        </div>
                        <div class="status-indicator disconnected">
                            <i class="ri-close-circle-line me-2"></i>
                            Not Configured
                        </div>
                    </div>

                    <!-- FFmpeg Status Check -->
                    <div class="ffmpeg-status not-installed">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>FFmpeg Status: Not Detected</strong>
                                <p class="mb-0">FFmpeg is required for video processing</p>
                            </div>
                            <button class="btn btn-outline-dark btn-sm" onclick="checkFFmpeg()">
                                <i class="ri-refresh-line"></i>
                                Check Again
                            </button>
                        </div>
                    </div>

                    <form id="videoConfigForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ffmpeg_path" class="form-label">
                                        <i class="ri-terminal-line text-primary me-2"></i>
                                        FFmpeg Path
                                    </label>
                                    <input type="text" class="form-control" id="ffmpeg_path" name="ffmpeg_path" 
                                           placeholder="C:\ffmpeg\ffmpeg-7.1.1-essentials_build\bin\ffmpeg.exe" 
                                           value="<?php 
                                           if (isset($ffmpeg_config['ffmpeg_path']['value']) && 
                                               !empty($ffmpeg_config['ffmpeg_path']['value']) && 
                                               $ffmpeg_config['ffmpeg_path']['value'] !== 'ffmpeg') {
                                               echo esc($ffmpeg_config['ffmpeg_path']['value']);
                                           } else {
                                               echo 'C:\\ffmpeg\\ffmpeg-7.1.1-essentials_build\\bin\\ffmpeg.exe';
                                           }
                                           ?>">
                                    <div class="form-text">Path to FFmpeg executable</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_video_size" class="form-label">
                                        <i class="ri-file-line text-primary me-2"></i>
                                        Max Video Size (MB)
                                    </label>
                                    <input type="number" class="form-control" id="max_video_size" name="max_video_size" 
                                           value="<?= isset($ffmpeg_config['max_video_size']) ? esc($ffmpeg_config['max_video_size']['value']) : '100' ?>" 
                                           min="10" max="1000">
                                    <div class="form-text">Maximum video file size for processing</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="video_quality" class="form-label">
                                        <i class="ri-hd-line text-primary me-2"></i>
                                        Video Quality
                                    </label>
                                    <select class="form-select" id="video_quality" name="video_quality">
                                        <option value="high" <?= (isset($ffmpeg_config['video_quality']) && $ffmpeg_config['video_quality']['value'] == 'high') ? 'selected' : '' ?>>High Quality (slower)</option>
                                        <option value="medium" <?= (!isset($ffmpeg_config['video_quality']) || $ffmpeg_config['video_quality']['value'] == 'medium') ? 'selected' : '' ?>>Medium Quality (balanced)</option>
                                        <option value="low" <?= (isset($ffmpeg_config['video_quality']) && $ffmpeg_config['video_quality']['value'] == 'low') ? 'selected' : '' ?>>Low Quality (faster)</option>
                                    </select>
                                    <div class="form-text">Balance between quality and file size</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="output_format" class="form-label">
                                        <i class="ri-file-video-line text-primary me-2"></i>
                                        Default Output Format
                                    </label>
                                    <select class="form-select" id="output_format" name="output_format">
                                        <option value="mp4" <?= (!isset($ffmpeg_config['output_format']) || $ffmpeg_config['output_format']['value'] == 'mp4') ? 'selected' : '' ?>>MP4 (Recommended)</option>
                                        <option value="webm" <?= (isset($ffmpeg_config['output_format']) && $ffmpeg_config['output_format']['value'] == 'webm') ? 'selected' : '' ?>>WebM</option>
                                        <option value="avi" <?= (isset($ffmpeg_config['output_format']) && $ffmpeg_config['output_format']['value'] == 'avi') ? 'selected' : '' ?>>AVI</option>
                                        <option value="mov" <?= (isset($ffmpeg_config['output_format']) && $ffmpeg_config['output_format']['value'] == 'mov') ? 'selected' : '' ?>>QuickTime</option>
                                    </select>
                                    <div class="form-text">Default format for processed videos</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_resolution" class="form-label">
                                        <i class="ri-aspect-ratio-line text-primary me-2"></i>
                                        Max Resolution
                                    </label>
                                    <select class="form-select" id="max_resolution" name="max_resolution">
                                        <option value="720p" <?= (isset($ffmpeg_config['max_resolution']) && $ffmpeg_config['max_resolution']['value'] == '720p') ? 'selected' : '' ?>>720p (HD)</option>
                                        <option value="1080p" <?= (!isset($ffmpeg_config['max_resolution']) || $ffmpeg_config['max_resolution']['value'] == '1080p') ? 'selected' : '' ?>>1080p (Full HD)</option>
                                        <option value="1440p" <?= (isset($ffmpeg_config['max_resolution']) && $ffmpeg_config['max_resolution']['value'] == '1440p') ? 'selected' : '' ?>>1440p (2K)</option>
                                        <option value="2160p" <?= (isset($ffmpeg_config['max_resolution']) && $ffmpeg_config['max_resolution']['value'] == '2160p') ? 'selected' : '' ?>>2160p (4K)</option>
                                    </select>
                                    <div class="form-text">Maximum output resolution</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="thumbnail_time" class="form-label">
                                        <i class="ri-image-line text-primary me-2"></i>
                                        Thumbnail Time (seconds)
                                    </label>
                                    <input type="number" class="form-control" id="thumbnail_time" name="thumbnail_time" 
                                           value="<?= isset($ffmpeg_config['thumbnail_time']) ? esc($ffmpeg_config['thumbnail_time']['value']) : '5' ?>" 
                                           min="1" max="60">
                                    <div class="form-text">Time in video to capture thumbnail</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="generate_thumbnails" name="generate_thumbnails" 
                                               value="1" <?= (isset($ffmpeg_config['generate_thumbnails']) && $ffmpeg_config['generate_thumbnails']['value'] == '1') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="generate_thumbnails">
                                            <i class="ri-image-add-line text-primary me-2"></i>
                                            Generate Thumbnails Automatically
                                        </label>
                                    </div>
                                    <div class="form-text">Create thumbnail images for uploaded videos</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-outline-secondary me-2" onclick="checkFFmpeg()">
                                <i class="ri-refresh-line me-1"></i>Check FFmpeg
                            </button>
                            <button type="button" class="btn btn-info me-2" onclick="testVideoProcessing()">
                                <i class="ri-play-line me-1"></i>Test Processing
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Configuration
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Processing Statistics -->
                <div class="video-card">
                    <h5>
                        <i class="ri-bar-chart-line text-primary me-2"></i>
                        Processing Statistics
                    </h5>
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="h4 mb-1">0</div>
                            <small class="text-muted">Videos Processed</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h4 mb-1">0 MB</div>
                            <small class="text-muted">Space Saved</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h4 mb-1">0</div>
                            <small class="text-muted">Thumbnails Created</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h4 mb-1">00:00</div>
                            <small class="text-muted">Avg Processing Time</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Supported Formats -->
                <div class="video-card">
                    <h6>
                        <i class="ri-file-video-line text-info me-2"></i>
                        Supported Formats
                    </h6>
                    <div class="format-grid">
                        <div class="format-badge supported">MP4</div>
                        <div class="format-badge supported">AVI</div>
                        <div class="format-badge supported">MOV</div>
                        <div class="format-badge supported">WMV</div>
                        <div class="format-badge supported">FLV</div>
                        <div class="format-badge supported">WebM</div>
                        <div class="format-badge supported">MKV</div>
                        <div class="format-badge supported">3GP</div>
                    </div>
                </div>

                <!-- FFmpeg Installation Guide -->
                <div class="video-card">
                    <h6>
                        <i class="ri-guide-line text-info me-2"></i>
                        FFmpeg Installation
                    </h6>
                    <div class="mb-3">
                        <strong>Ubuntu/Debian:</strong>
                        <code class="d-block bg-light p-2 mt-1">sudo apt install ffmpeg</code>
                    </div>
                    <div class="mb-3">
                        <strong>CentOS/RHEL:</strong>
                        <code class="d-block bg-light p-2 mt-1">sudo yum install ffmpeg</code>
                    </div>
                    <div class="mb-3">
                        <strong>Windows:</strong>
                        <p class="text-muted small">Download from <a href="https://ffmpeg.org/download.html" target="_blank">ffmpeg.org</a></p>
                    </div>
                    <div class="mb-3">
                        <strong>macOS:</strong>
                        <code class="d-block bg-light p-2 mt-1">brew install ffmpeg</code>
                    </div>
                </div>

                <!-- Performance Tips -->
                <div class="video-card">
                    <h6>
                        <i class="ri-lightbulb-line text-warning me-2"></i>
                        Performance Tips
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-2"></i>
                            Use hardware acceleration when available
                        </li>
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-2"></i>
                            Process videos asynchronously
                        </li>
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-2"></i>
                            Set appropriate quality settings
                        </li>
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-2"></i>
                            Monitor server resources
                        </li>
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-2"></i>
                            Use CDN for video delivery
                        </li>
                    </ul>
                </div>

                <!-- Processing Queue -->
                <div class="video-card">
                    <h6>
                        <i class="ri-timer-line text-primary me-2"></i>
                        Processing Queue
                    </h6>
                    <div class="text-center text-muted">
                        <i class="ri-inbox-line" style="font-size: 2rem;"></i>
                        <p class="mt-2">No videos in queue</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function checkFFmpeg() {
    const statusDiv = document.querySelector('.ffmpeg-status');
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="ri-loader-4-line"></i>';
    btn.disabled = true;
    
    // Test FFmpeg installation
    fetch('<?= base_url('integrations/testFFmpeg') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'test=1'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusDiv.className = 'ffmpeg-status installed';
            statusDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>FFmpeg Status: Installed</strong>
                        <p class="mb-0">Version ${data.version} detected</p>
                        ${data.codecs.length > 0 ? `<small class="text-muted">Codecs: ${data.codecs.join(', ')}</small>` : ''}
                    </div>
                    <button class="btn btn-outline-success btn-sm" onclick="checkFFmpeg()">
                        <i class="ri-check-line"></i>
                        Detected
                    </button>
                </div>
            `;
            
            // Update main status
            const statusIndicator = document.querySelector('.status-indicator');
            statusIndicator.className = 'status-indicator connected';
            statusIndicator.innerHTML = '<i class="ri-check-circle-line me-2"></i>Ready';
            
            Swal.fire('Success!', data.message, 'success');
        } else {
            statusDiv.className = 'ffmpeg-status not-installed';
            statusDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>FFmpeg Status: Not Detected</strong>
                        <p class="mb-0">FFmpeg is required for video processing</p>
                    </div>
                    <button class="btn btn-outline-dark btn-sm" onclick="checkFFmpeg()">
                        <i class="ri-refresh-line"></i>
                        Check Again
                    </button>
                </div>
            `;
            
            Swal.fire('Error!', data.message, 'error');
        }
        
        btn.innerHTML = originalText;
        btn.disabled = false;
    })
    .catch(error => {
        console.error('Error:', error);
        statusDiv.className = 'ffmpeg-status not-installed';
        statusDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>FFmpeg Status: Error</strong>
                    <p class="mb-0">Unable to check FFmpeg status</p>
                </div>
                <button class="btn btn-outline-dark btn-sm" onclick="checkFFmpeg()">
                    <i class="ri-refresh-line"></i>
                    Check Again
                </button>
            </div>
        `;
        
        btn.innerHTML = originalText;
        btn.disabled = false;
        Swal.fire('Error!', 'Connection test failed. Please make sure you are logged in and try again.', 'error');
    });
}

function testVideoProcessing() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="ri-loader-4-line me-2"></i>Testing...';
    btn.disabled = true;
    
    // Test video processing
    fetch('<?= base_url('integrations/test-connection') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'service_name=ffmpeg'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            btn.innerHTML = '<i class="ri-check-line me-2"></i>Test Successful!';
            btn.classList.remove('btn-info');
            btn.classList.add('btn-success');
            
            Swal.fire('Success!', data.message, 'success');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-info');
                btn.disabled = false;
            }, 3000);
        } else {
            btn.innerHTML = '<i class="ri-close-line me-2"></i>Test Failed';
            btn.classList.remove('btn-info');
            btn.classList.add('btn-danger');
            
            Swal.fire('Error!', data.message, 'error');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-info');
                btn.disabled = false;
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.innerHTML = '<i class="ri-close-line me-2"></i>Test Failed';
        btn.classList.remove('btn-info');
        btn.classList.add('btn-danger');
        
        Swal.fire('Error!', 'Connection test failed', 'error');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-info');
            btn.disabled = false;
        }, 3000);
    });
}

document.getElementById('videoConfigForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i>Saving...';
    submitBtn.disabled = true;
    
    const formData = new FormData(this);
    
    fetch('<?= base_url('integrations/save') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(formData).toString() + '&service_name=ffmpeg'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            submitBtn.innerHTML = '<i class="ri-check-line me-2"></i>Saved!';
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-success');
            
            Swal.fire('Success!', data.message, 'success');
            
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.classList.remove('btn-success');
                submitBtn.classList.add('btn-primary');
                submitBtn.disabled = false;
            }, 1500);
        } else {
            submitBtn.innerHTML = '<i class="ri-close-line me-2"></i>Error';
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-danger');
            
            Swal.fire('Error!', data.message, 'error');
            
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.classList.remove('btn-danger');
                submitBtn.classList.add('btn-primary');
                submitBtn.disabled = false;
            }, 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        submitBtn.innerHTML = '<i class="ri-close-line me-2"></i>Error';
        submitBtn.classList.remove('btn-primary');
        submitBtn.classList.add('btn-danger');
        
        Swal.fire('Error!', 'Failed to save configuration', 'error');
        
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.classList.remove('btn-danger');
            submitBtn.classList.add('btn-primary');
            submitBtn.disabled = false;
        }, 1500);
    });
});

// Check FFmpeg status on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set default FFmpeg path if field is empty or shows just 'ffmpeg'
    const ffmpegPathField = document.getElementById('ffmpeg_path');
    if (!ffmpegPathField.value || 
        ffmpegPathField.value === 'ffmpeg' || 
        ffmpegPathField.value.trim() === 'ffmpeg') {
        ffmpegPathField.value = 'C:\\ffmpeg\\ffmpeg-7.1.1-essentials_build\\bin\\ffmpeg.exe';
        console.log('Set default FFmpeg path from:', ffmpegPathField.value);
    } else {
        console.log('FFmpeg path already set to:', ffmpegPathField.value);
    }
    
    // Auto-check FFmpeg status after a brief delay
    setTimeout(() => {
        console.log('Auto-checking FFmpeg status...');
        checkFFmpeg();
    }, 1500);
});
</script>
<?= $this->endSection() ?> 