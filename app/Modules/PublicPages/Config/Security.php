<?php

namespace Modules\PublicPages\Config;

use CodeIgniter\Config\BaseConfig;

class Security extends BaseConfig
{
    /**
     * Content Security Policy (CSP) settings
     */
    public array $csp = [
        'default-src' => ["'self'"],
        'script-src' => ["'self'", "'unsafe-inline'", 'https://cdn.jsdelivr.net', 'https://cdnjs.cloudflare.com'],
        'style-src' => ["'self'", "'unsafe-inline'", 'https://cdn.jsdelivr.net', 'https://cdnjs.cloudflare.com'],
        'img-src' => ["'self'", 'data:', 'https:'],
        'font-src' => ["'self'", 'https://fonts.gstatic.com'],
        'connect-src' => ["'self'"],
        'media-src' => ["'self'"],
        'object-src' => ["'none'"],
        'child-src' => ["'none'"],
        'frame-src' => ["'none'"],
        'worker-src' => ["'none'"],
        'frame-ancestors' => ["'none'"],
        'form-action' => ["'self'"],
        'base-uri' => ["'self'"],
        'manifest-src' => ["'self'"]
    ];

    /**
     * Allowed HTML tags for content sanitization
     */
    public array $allowedHtmlTags = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 'strike', 's',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li', 'dl', 'dt', 'dd',
        'a', 'img', 'figure', 'figcaption',
        'blockquote', 'cite', 'q',
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'th', 'caption',
        'div', 'span', 'section', 'article', 'aside', 'header', 'footer', 'main',
        'pre', 'code', 'kbd', 'samp', 'var',
        'sub', 'sup', 'mark', 'small',
        'hr', 'address', 'time'
    ];

    /**
     * Allowed HTML attributes
     */
    public array $allowedHtmlAttributes = [
        'global' => ['class', 'id', 'title', 'lang', 'dir'],
        'a' => ['href', 'target', 'rel', 'download'],
        'img' => ['src', 'alt', 'width', 'height', 'loading'],
        'table' => ['border', 'cellpadding', 'cellspacing'],
        'td' => ['colspan', 'rowspan', 'headers'],
        'th' => ['colspan', 'rowspan', 'scope', 'headers'],
        'blockquote' => ['cite'],
        'q' => ['cite'],
        'time' => ['datetime'],
        'figure' => [],
        'figcaption' => []
    ];

    /**
     * Dangerous HTML tags that should always be removed
     */
    public array $dangerousHtmlTags = [
        'script', 'style', 'link', 'meta', 'title', 'head', 'body', 'html',
        'iframe', 'frame', 'frameset', 'noframes',
        'object', 'embed', 'applet', 'param',
        'form', 'input', 'textarea', 'select', 'option', 'button',
        'base', 'basefont', 'bgsound',
        'xml', 'xmp', 'plaintext'
    ];

    /**
     * Dangerous HTML attributes that should always be removed
     */
    public array $dangerousHtmlAttributes = [
        // Event handlers
        'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate',
        'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus',
        'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate',
        'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu',
        'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged',
        'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend',
        'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop',
        'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus',
        'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup',
        'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter',
        'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup',
        'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste',
        'onpropertychange', 'onreadystatechange', 'onreset', 'onresize',
        'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete',
        'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange',
        'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload',
        
        // Other dangerous attributes
        'style', 'background', 'dynsrc', 'lowsrc'
    ];

    /**
     * File upload security settings
     */
    public array $fileUpload = [
        'maxSize' => 50 * 1024 * 1024, // 50MB
        'allowedMimeTypes' => [
            // Images
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
            // Documents
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            // Videos
            'video/mp4', 'video/webm',
            // Audio
            'audio/mpeg', 'audio/wav', 'audio/ogg'
        ],
        'blockedExtensions' => [
            'php', 'php3', 'php4', 'php5', 'phtml', 'asp', 'aspx', 'jsp',
            'js', 'html', 'htm', 'exe', 'bat', 'cmd', 'com', 'pif', 'scr',
            'vbs', 'jar', 'sh', 'py', 'pl', 'rb', 'cgi'
        ]
    ];

    /**
     * Rate limiting settings
     */
    public array $rateLimiting = [
        'pageViews' => [
            'window' => 300, // 5 minutes
            'maxAttempts' => 10
        ],
        'likes' => [
            'window' => 60, // 1 minute
            'maxAttempts' => 3
        ],
        'fileUploads' => [
            'window' => 3600, // 1 hour
            'maxAttempts' => 20
        ]
    ];

    /**
     * Content sanitization settings
     */
    public array $contentSanitization = [
        'stripDangerousTags' => true,
        'stripDangerousAttributes' => true,
        'convertEntities' => true,
        'removeJavaScript' => true,
        'removeDataUrls' => true,
        'maxContentLength' => 100000, // 100KB
        'maxCssLength' => 10000,     // 10KB
        'maxJsLength' => 10000       // 10KB
    ];

    /**
     * User role permissions
     */
    public array $rolePermissions = [
        'admin' => [
            'create_pages' => true,
            'edit_all_pages' => true,
            'delete_all_pages' => true,
            'use_custom_css' => true,
            'use_custom_js' => true,
            'upload_files' => true,
            'manage_versions' => true,
            'view_analytics' => true
        ],
        'staff' => [
            'create_pages' => true,
            'edit_own_pages' => true,
            'delete_own_pages' => true,
            'use_custom_css' => false,
            'use_custom_js' => false,
            'upload_files' => true,
            'manage_versions' => true,
            'view_analytics' => true
        ],
        'user' => [
            'create_pages' => false,
            'edit_own_pages' => false,
            'delete_own_pages' => false,
            'use_custom_css' => false,
            'use_custom_js' => false,
            'upload_files' => false,
            'manage_versions' => false,
            'view_analytics' => false
        ]
    ];
}
