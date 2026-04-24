<?php
/**
 * Single Post Template
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$_post_id = get_the_ID();

// Post header (title, meta, featured image)
get_template_part(
	'templates/blocks/post-header', null, [
		'class'   => 'mb-2',
		'post_id' => $_post_id,
	]
);

// Post content (body text)
get_template_part(
	'templates/blocks/post-content', null, [
		'class'         => 'mb-2',
		'post_id'       => $_post_id,
		'content_class' => 'col-lg-8 mx-auto',
	]
);

// Post footer (tags, author bio)
get_template_part(
	'templates/blocks/post-footer', null, [
		'class'   => 'mb-2',
		'post_id' => $_post_id,
	]
);

// Related posts
get_template_part(
	'templates/blocks/related-posts', null, [
		'post_id' => $_post_id,
	]
);
