<?php
/**
 * Single Post Template
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$post_thumbnail_id = has_post_thumbnail() ? get_post_thumbnail_id() : null;

if ( ! empty( $post_thumbnail_id ) ) :
	get_template_part(
		'templates/blocks/hero-post', null, [
			'class' => 'bg-primary text-white mb-2 mb-lg-4',
			'title' => get_the_title(),
			'image' => $post_thumbnail_id,
		]
	);
else :
	get_template_part(
		'templates/blocks/hero-title', null, [
			'class' => 'bg-secondary text-dark mt-1 mb-2',
			'title' => get_the_title(),
		]
	);
endif;

get_template_part(
	'templates/blocks/content-area', null, [
		'class' => 'my-4',
		'content_class' => 'col-lg-8 mx-auto',
		'show_sidebar' => false,
	]
);
