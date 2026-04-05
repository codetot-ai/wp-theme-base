<?php
/**
 * Theme Functions
 *
 * @package codetot
 */

// Theme constants.
define( 'CODETOT_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'CODETOT_DIR', get_stylesheet_directory() );
define( 'CODETOT_URI', get_stylesheet_directory_uri() );

// Core.
require_once CODETOT_DIR . '/inc/setup.php';
require_once CODETOT_DIR . '/inc/cleanup.php';
require_once CODETOT_DIR . '/inc/enqueue.php';
require_once CODETOT_DIR . '/inc/critical-css.php';

// Helpers.
require_once CODETOT_DIR . '/inc/helpers/env.php';
require_once CODETOT_DIR . '/inc/helpers/cdn.php';
require_once CODETOT_DIR . '/inc/helpers/formatting.php';
require_once CODETOT_DIR . '/inc/helpers/template-tags.php';

// Layouts.
require_once CODETOT_DIR . '/inc/layouts/global.php';
require_once CODETOT_DIR . '/inc/layouts/archive.php';
require_once CODETOT_DIR . '/inc/layouts/singular.php';
