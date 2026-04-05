<?php
/**
 * Clean up WordPress default HTML output.
 *
 * @package codetot
 */

defined( 'ABSPATH' ) || exit;

/**
 * Remove unnecessary tags from wp_head.
 */
function codetot_cleanup_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
}
add_action( 'after_setup_theme', 'codetot_cleanup_head' );

/**
 * Disable WordPress emoji scripts and styles.
 */
function codetot_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'codetot_disable_emojis' );

/**
 * Remove emoji DNS prefetch.
 */
function codetot_remove_emoji_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$urls = array_filter(
			$urls,
			function ( $url ) {
				return ! is_string( $url ) || false === strpos( $url, 'https://s.w.org/images/core/emoji/' );
			}
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'codetot_remove_emoji_dns_prefetch', 10, 2 );

/**
 * Remove global styles and SVG filters from block editor.
 */
function codetot_remove_block_editor_styles() {
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_footer', 'wp_enqueue_stored_styles', 1 );
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
}
add_action( 'after_setup_theme', 'codetot_remove_block_editor_styles' );

/**
 * Dequeue block library CSS on the frontend.
 */
function codetot_dequeue_block_styles() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'codetot_dequeue_block_styles', 100 );

/**
 * Remove type attribute from script and style tags (HTML5).
 */
function codetot_remove_type_attribute( $tag ) {
	return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
}
add_filter( 'style_loader_tag', 'codetot_remove_type_attribute' );
add_filter( 'script_loader_tag', 'codetot_remove_type_attribute' );

/**
 * Remove pingback header.
 */
function codetot_remove_pingback( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}
add_filter( 'wp_headers', 'codetot_remove_pingback' );
