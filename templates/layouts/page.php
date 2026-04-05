<?php
/**
 * Default Page Template Layout
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$_post_id = get_the_ID();

// hero-title
get_template_part(
	'templates/blocks/hero-title', null, [
		'class' => 'text-center bg-primary text-white mb-2 mb-lg-4',
		'title' => get_the_title($_post_id),
	]
);

// main content, 8 columns

get_template_part(
	'templates/blocks/content-area', null, [
		'class' => 'mb-2 mb-lg-4',
		'post_id' => $_post_id,
		'content_class' => 'col-lg-8 mx-auto',
	]
);
