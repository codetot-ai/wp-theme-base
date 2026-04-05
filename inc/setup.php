<?php
/**
 * Theme Setup
 *
 * @package codetot
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports, menus, and image sizes.
 */
function codetot_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'automatic-feed-links' );

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Menu', 'codetot' ),
			'footer-menu'  => __( 'Footer Menu', 'codetot' ),
		)
	);
}
add_action( 'after_setup_theme', 'codetot_theme_setup' );

/**
 * Disable XML-RPC.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'the_generator', '__return_empty_string' );
