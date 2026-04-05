<?php
/**
 * Main Template
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

get_header();

if ( is_page() ) {
	get_template_part('templates/layouts/page');
}

if ( is_single() ) {
	get_template_part('templates/layouts/single');
}

get_footer();
