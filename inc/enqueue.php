<?php
/**
 * Enqueue Scripts and Styles
 *
 * CSS load order in <head>:
 * 1. Font preload (priority 0)
 * 2. Font CSS inline (priority 1)
 * 3. Bootstrap CSS inline (priority 2)
 * 4. Variables CSS inline (priority 2)
 * 5. Critical CSS inline (priority 2) — see critical-css.php
 * 6. Main theme CSS deferred (default priority)
 *
 * @package codetot
 */

defined( 'ABSPATH' ) || exit;

/**
 * Preload critical font files.
 *
 * Add your .woff2 font paths here when self-hosting fonts.
 * Keep empty for system font stack.
 */
function codetot_preload_fonts() {
	$fonts = array(
		// Example: '/assets/fonts/your-font-latin.woff2',
	);

	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( CODETOT_URI . $font )
		);
	}
}
add_action( 'wp_head', 'codetot_preload_fonts', 0 );

/**
 * Inline font CSS in <head>.
 */
function codetot_inline_font_css() {
	$font_css_path = CODETOT_DIR . '/assets/dist/font.css';
	if ( ! file_exists( $font_css_path ) ) {
		return;
	}

	$css = file_get_contents( $font_css_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( empty( trim( $css ) ) ) {
		return;
	}

	// Fix relative paths — inlined CSS resolves relative to HTML, not CSS file.
	$css = str_replace( '../fonts/', CODETOT_URI . '/assets/fonts/', $css );
	echo '<style id="font-css">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'codetot_inline_font_css', 1 );

/**
 * Inline Bootstrap CSS in <head>.
 */
function codetot_inline_bootstrap_css() {
	$bootstrap_css_path = CODETOT_DIR . '/assets/dist/bootstrap.css';
	if ( ! file_exists( $bootstrap_css_path ) ) {
		return;
	}

	$css = file_get_contents( $bootstrap_css_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	echo '<style id="bootstrap-css">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'codetot_inline_bootstrap_css', 2 );

/**
 * Inline CSS variables in <head>.
 */
function codetot_inline_variables_css() {
	$variables_path = CODETOT_DIR . '/variables.css';
	if ( ! file_exists( $variables_path ) ) {
		return;
	}

	$css = file_get_contents( $variables_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	// Remove @custom-media declarations (not needed inline).
	$css = preg_replace( '/@custom-media[^;]*;/i', '', $css );
	// Minify whitespace.
	$css = preg_replace( '/\s+/', ' ', trim( $css ) );

	if ( ! empty( $css ) ) {
		echo '<style id="variables-css">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_head', 'codetot_inline_variables_css', 2 );

/**
 * Enqueue main front-end styles and scripts.
 */
function codetot_enqueue_assets() {
	// Main stylesheet (compiled by Vite).
	$css_file = CODETOT_DIR . '/assets/dist/main.css';
	wp_enqueue_style(
		'codetot-style',
		CODETOT_URI . '/assets/dist/main.css',
		array(),
		file_exists( $css_file ) ? filemtime( $css_file ) : CODETOT_VERSION
	);

	// Main script (compiled by Vite).
	$js_file = CODETOT_DIR . '/assets/dist/main.js';
	wp_enqueue_script(
		'codetot-script',
		CODETOT_URI . '/assets/dist/main.js',
		array(),
		file_exists( $js_file ) ? filemtime( $js_file ) : CODETOT_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'codetot_enqueue_assets' );
