<?php

if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
});

function rewrite_asset_urls_to_s3($url) {
    $cdn_base = 'https://tarumt-grad-hub-bucket.s3.us-east-1.amazonaws.com';

    // Prevent rewriting if it's already an S3 URL
    if (strpos($url, $cdn_base) !== false) {
        return $url;
    }

    // Get the current site's base URL (e.g., http://elb.amazonaws.com/tarumtgradhub)
    $site_url = site_url();

    // If $url starts with the site_url, strip it and keep only the path
    if (strpos($url, $site_url) === 0) {
        $path = substr($url, strlen($site_url));

        // Clean leading slash just in case
        $path = '/' . ltrim($path, '/');

        // Return full S3 path
        return rtrim($cdn_base, '/') . $path;
    }

    // Otherwise, return original
    return $url;
}

add_filter('script_loader_src', 'rewrite_asset_urls_to_s3');
add_filter('style_loader_src', 'rewrite_asset_urls_to_s3');

