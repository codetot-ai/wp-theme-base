<?php
/**
 * Setup environments
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

/**
 * Check if local host
 *
 * @return array
 */
function codetot_is_localhost() {
	return ! empty($_SERVER['HTTP_X_WP_THEME_ENV']) && 'development' === $_SERVER['HTTP_X_WP_THEME_ENV'];
}
