<?php
/**
 * Default Page Template Layout
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$_post_id = get_the_ID();

get_template_part(
	'templates/blocks/breadcrumb', null, [
		'class' => 'mb-1',
	]
);

get_template_part(
	'templates/blocks/content-area', null, [
		'class'         => 'py-2 py-lg-4',
		'post_id'       => $_post_id,
		'content_class' => 'col-lg-8 mx-auto',
	]
);
