<?php
/**
 * Block: Post Grid Loop
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'post_ids' => [],
	'column_class' => 'd-flex flex-column col-12 col-md-6 col-lg-4 mb-2',
]);


if ( !empty( $data['post_ids']) ) :
	foreach ( $data['post_ids'] as $_post_id ) : ?>
		<div class="<?php echo esc_attr($data['column_class']); ?>">
			<?php get_template_part( 'templates/blocks/post-card', null, [
				'class' => 'd-flex flex-column flex-grow-1',
				'post_id' => $_post_id,
			] ); ?>
		</div>
	<?php
	endforeach;
	wp_reset_postdata();
endif;
