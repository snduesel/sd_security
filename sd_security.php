<?php
defined('ABSPATH') or die("Forbidden");
/**
* Plugin Name: SD Security
* Plugin URI: https://www.steffen-duesel.de
* Description: Security Plugin for WordPress
* Version: 1.0.0
* Author: Steffen Duesel
* Author URI: https://www.steffen-duesel.de
*/
	
// block WP enum scans
if (!is_admin()) {
	// default URL format
	if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) die();
	add_filter('redirect_canonical', 'shapeSpace_check_enum', 10, 2);
}
function shapeSpace_check_enum($redirect, $request) {
	// permalink URL format
	if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) die();
	else return $redirect;
}

// remove wp version param from any enqueued scripts
function at_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'at_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'at_remove_wp_ver_css_js', 9999 );

// remove wp version meta tag and from rss feed
add_filter('the_generator', '__return_false');

// remove unnecessary header information
function remove_header_info() {
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'start_post_rel_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head',10,0); // for WordPress >= 3.0
}
add_action('init', 'remove_header_info');

/*Disable ping back scanner and complete xmlrpc class. */
add_filter( 'wp_xmlrpc_server_class', '__return_false' );
add_filter('xmlrpc_enabled', '__return_false');

//Remove error mesage in login
add_filter('login_errors',create_function('$a', "return 'Invalid Input';"));

// remove various feeds
function fb_disable_feed() {
wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
}
add_action('do_feed', 'fb_disable_feed', 1);
add_action('do_feed_rdf', 'fb_disable_feed', 1);
add_action('do_feed_rss', 'fb_disable_feed', 1);
#add_action('do_feed_rss2', 'fb_disable_feed', 1);
add_action('do_feed_atom', 'fb_disable_feed', 1);
add_action('do_feed_rss2_comments', 'fb_disable_feed', 1);
add_action('do_feed_atom_comments', 'fb_disable_feed', 1);
show_admin_bar( false );

#disable redirect to login page:
function rbz_prevent_multisite_signup() 
{
    wp_redirect( site_url() );
    die();
}
add_action( 'signup_header', 'rbz_prevent_multisite_signup' );

//remove xpingback header
function remove_x_pingback($headers) {
    unset($headers['X-Pingback']);
    return $headers;
}
add_filter('wp_headers', 'remove_x_pingback');
