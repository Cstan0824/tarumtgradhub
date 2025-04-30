<?php

if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
});

function rewrite_asset_urls_to_s3($url) {
    $cdn_base = 'https://tarumt-grad-hub-bucket.s3.us-east-1.amazonaws.com';

    if (strpos($url, '/wp-includes/') !== false || strpos($url, '/wp-content/') !== false) {
        $parsed = parse_url($url);
        if (isset($parsed['path'])) {
            $url = $cdn_base . $parsed['path'];
        }
    }

    return $url;
}

add_filter('script_loader_src', 'rewrite_asset_urls_to_s3');
add_filter('style_loader_src', 'rewrite_asset_urls_to_s3');

