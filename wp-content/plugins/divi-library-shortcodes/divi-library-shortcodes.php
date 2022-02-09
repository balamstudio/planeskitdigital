<?php
/*
Plugin Name: Divi Library Shortcodes
Plugin URL: https://divilife.com/
Description: Add any Divi Library item to any WYSIWYG editor easily!
Version: 1.0
Author: Divi Life — Tim Strifler
Author URI: https://divilife.com
*/


// Make sure we don't expose any info if called directly or may someone integrates this plugin in a theme
if ( class_exists('DiviLibShortcode') || !defined('ABSPATH') || !function_exists( 'add_action' ) ) {
	
	return;
}

define( 'DIVI_LIBSHORT_VERSION', '1.0');
define( 'DIVI_LIBSHORT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'DIVI_LIBSHORT_PLUGIN_NAME', 'DiviLibShortcode' );
define( 'DIVI_LIBSHORT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DIVI_LIBSHORT_PLUGIN_URL', plugin_dir_url( __FILE__ ));

require_once( DIVI_LIBSHORT_PLUGIN_DIR . '/class.divi-library-shortcodes.core.php' );

add_action( 'init', array( 'DiviLibShortcode', 'init' ) );
	
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	
	require_once( DIVI_LIBSHORT_PLUGIN_DIR . '/class.divi-library-shortcodes.admin.core.php' );
	add_action( 'init', array( 'DiviLibShortcode_Admin', 'init' ) );
}