<?php
/**
 * Formatter
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

/**
 * Remove unused css
 *
 * @param string $css
 * @return string
 */
function codetot_format_css_variables( $css ) {
	// Remove unused css
	$css = preg_replace('/@custom-media --(.*)\;/i', '', $css);

	// Remove empty whitespace
	$css = preg_replace('/\s+/', '', $css);

	return $css;
}
