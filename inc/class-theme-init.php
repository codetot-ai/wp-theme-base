<?php
/**
 * Theme Init Class
 *
 * Handles initialization of theme features, assets, menus, and performance optimizations.
 *
 * @package codetot
 */

namespace Codetot\Theme;

require_once get_theme_file_path( 'inc/helpers/env.php' );
require_once get_theme_file_path( 'inc/helpers/cdn.php' );
require_once get_theme_file_path( 'inc/helpers/formatting.php' );
require_once get_theme_file_path( 'inc/helpers/template-tags.php' );

require_once get_theme_file_path( 'inc/layouts/global.php' );
require_once get_theme_file_path( 'inc/layouts/archive.php' );
require_once get_theme_file_path( 'inc/layouts/singular.php' );

/**
 * Theme initialization class.
 */
class Theme_Init {

	/**
	 * Theme version (cached or timestamp in debug mode).
	 *
	 * @var string
	 */
	public $theme_version;

	/**
	 * File suffix for minified assets (empty on localhost).
	 *
	 * @var string
	 */
	public $theme_env;

	/**
	 * Constructor.
	 *
	 * Sets up theme version, environment, hooks, and filters.
	 */
	public function __construct() {

		$this->theme_version = WP_DEBUG ? time() : wp_get_theme()->get( 'Version' );
		$this->theme_env     = codetot_is_localhost() ? '' : '.min';

		add_action( 'wp_enqueue_scripts', [ $this, 'critical_frontend_assets' ], 1 );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_frontend_assets' ], 60 );

		add_action( 'after_setup_theme', [ $this, 'theme_supports' ] );

		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'the_generator', '__return_empty_string' );

		add_action( 'init', [ $this, 'disable_emojis' ] );
	}

	/**
	 * Enqueue critical CSS (CSS variables).
	 *
	 * @return void
	 */
	public function critical_frontend_assets() {
		$variables_path = get_theme_file_path( 'variables.css' );

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$variables_css = file_exists( $variables_path ) ? file_get_contents( $variables_path ) : '';

		if ( ! empty( $variables_css ) ) {
			wp_register_style( 'codetot-variables', false, [], $this->theme_version );
			wp_enqueue_style( 'codetot-variables' );
			wp_add_inline_style( 'codetot-variables', codetot_format_css_variables( $variables_css ) );
		}
	}

	/**
	 * Register main frontend styles and scripts.
	 *
	 * @return void
	 */
	public function register_frontend_assets() {
		if ( ! codetot_is_localhost() ) {
			wp_enqueue_style( 'codetot-bootstrap', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css', [], $this->theme_version );
			wp_enqueue_style( 'codetot-frontend', get_stylesheet_directory_uri() . '/assets/css/frontend.min.css', [], $this->theme_version );
		}

		wp_enqueue_script( 'codetot-bootstrap', get_stylesheet_directory_uri() . '/assets/js/bootstrap' . $this->theme_env . '.js', [], $this->theme_version, true );
		wp_enqueue_script( 'codetot-frontend', get_stylesheet_directory_uri() . '/assets/js/frontend' . $this->theme_env . '.js', [], $this->theme_version, true );

		wp_enqueue_style( 'codetot-custom', get_stylesheet_uri(), [], $this->theme_version );
	}

	/**
	 * Declare theme support for various WordPress features.
	 *
	 * @return void
	 */
	public function theme_supports() {

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo' );

		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			]
		);

		add_theme_support( 'automatic-feed-links' );

		register_nav_menus(
			[
				'primary-menu'      => __( 'Primary Menu', 'codetot' ),
				'footer-menu'       => __( 'Footer Menu', 'codetot' ),
			]
		);
	}

	/**
	 * Remove emoji detection script and styles.
	 *
	 * @return void
	 */
	public function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'emoji_svg_url', '__return_false' );
	}
}

new Theme_Init();
