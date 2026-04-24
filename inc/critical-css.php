<?php
/**
 * Critical CSS — inline above-the-fold CSS and defer full stylesheet.
 *
 * @package codetot
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get the critical CSS file path for the current page type.
 *
 * @return string|false Path to critical CSS file, or false if not found.
 */
function codetot_get_critical_css_path() {
	$critical_dir = CODETOT_DIR . '/assets/dist/critical';

	// Post types that use the single critical CSS. Filter to add custom post types.
	$single_post_types = apply_filters( 'codetot_critical_css_single_types', [ 'post' ] );

	if ( is_front_page() ) {
		$file = $critical_dir . '/front-page.css';
	} elseif ( is_singular( $single_post_types ) ) {
		$file = $critical_dir . '/single.css';
	} elseif ( is_archive() || is_search() ) {
		$file = $critical_dir . '/archive.css';
	} else {
		$file = $critical_dir . '/page.css';
	}

	return file_exists( $file ) ? $file : false;
}

/**
 * Inline critical CSS in <head>.
 */
function codetot_inline_critical_css() {
	$critical_file = codetot_get_critical_css_path();

	if ( ! $critical_file ) {
		return;
	}

	$css = file_get_contents( $critical_file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( empty( $css ) ) {
		return;
	}

	echo '<style id="critical-css">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'codetot_inline_critical_css', 1 );

/**
 * Defer stylesheets when critical CSS exists.
 * Both Bootstrap and main CSS are deferred to avoid render-blocking.
 *
 * @param string $html   The link tag HTML.
 * @param string $handle The stylesheet handle.
 * @return string Modified link tag.
 */
function codetot_defer_main_stylesheet( $html, $handle ) {
	$deferred_handles = [ 'codetot-style', 'codetot-bootstrap' ];

	if ( ! in_array( $handle, $deferred_handles, true ) || ! codetot_get_critical_css_path() ) {
		return $html;
	}

	$html = str_replace(
		"media='all'",
		"media='print' onload=\"this.media='all'\"",
		$html
	);

	$noscript = str_replace( "media='print' onload=\"this.media='all'\"", "media='all'", $html );
	$html    .= '<noscript>' . $noscript . '</noscript>';

	return $html;
}
add_filter( 'style_loader_tag', 'codetot_defer_main_stylesheet', 10, 2 );
