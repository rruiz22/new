<?php

/**
 * UI Helper Functions
 *
 * @package     App
 * @subpackage  Helpers
 * @category    UI
 */

if (!function_exists('modal_anchor')) {
    /**
     * Modal Anchor
     *
     * Creates an anchor that will open a modal dialog
     *
     * @param string $url The URL to load in the modal
     * @param string $title The title/content of the anchor
     * @param array $attributes Additional attributes for the anchor tag
     * @return string The anchor HTML
     */
    function modal_anchor($url, $title, $attributes = [])
    {
        $attr = '';
        
        // Add modal related attributes
        $attributes['data-bs-toggle'] = 'modal';
        $attributes['data-bs-target'] = '#commonModal';
        $attributes['data-bs-remote'] = $url;
        
        foreach ($attributes as $key => $val) {
            $attr .= ' ' . $key . '="' . $val . '"';
        }
        
        return '<a href="javascript:;"' . $attr . '>' . $title . '</a>';
    }
}

if (!function_exists('js_anchor')) {
    /**
     * JavaScript Anchor
     *
     * Creates an anchor that will trigger a JavaScript action
     *
     * @param string $title The title/content of the anchor
     * @param array $attributes Additional attributes for the anchor tag
     * @return string The anchor HTML
     */
    function js_anchor($title, $attributes = [])
    {
        $attr = '';
        
        foreach ($attributes as $key => $val) {
            $attr .= ' ' . $key . '="' . $val . '"';
        }
        
        return '<a href="javascript:;"' . $attr . '>' . $title . '</a>';
    }
}

if (!function_exists('site_url')) {
    /**
     * Site URL
     * 
     * Returns the full URL to a site path
     *
     * @param string $path The path to append to the site URL
     * @return string The full URL
     */
    function site_url($path = '')
    {
        return base_url($path);
    }
} 