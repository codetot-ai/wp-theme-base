<?php
/**
 * Block: Related Posts
 *
 * @package codetot
 * @author codetot
 * @since 1.0.0
 */

$data = wp_parse_args(
	$args, [
		'class'   => '',
		'title'   => __( 'Related Posts', 'codetot' ),
		'count'   => 3,
		'post_id' => get_the_ID(),
	]
);

$_class = 'related-posts';
$_class .= ! empty( $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';

$categories   = get_the_category( $data['post_id'] );
$category_ids = [];

if ( ! empty( $categories ) ) {
	foreach ( $categories as $category ) {
		$category_ids[] = $category->term_id;
	}
}

if ( ! empty( $category_ids ) ) :
	$related_query = new WP_Query(
		[
			'category__in'   => $category_ids,
			'post__not_in'   => [ $data['post_id'] ],
			'posts_per_page' => $data['count'],
			'orderby'        => 'rand',
			'post_status'    => 'publish',
		]
	);

	if ( $related_query->have_posts() ) :
		$post_ids = wp_list_pluck( $related_query->posts, 'ID' );
		?>
		<div class="<?php echo esc_attr( $_class ); ?>" data-block="related-posts">
			<div class="container">
				<?php if ( ! empty( $data['title'] ) ) : ?>
					<h2 class="mb-2 related-posts__title"><?php echo esc_html( $data['title'] ); ?></h2>
				<?php endif; ?>
				<div class="row">
					<?php
					get_template_part(
						'templates/blocks/post-grid-loop', null, [
							'post_ids' => $post_ids,
						]
					);
					?>
				</div>
			</div>
		</div>
		<?php
		wp_reset_postdata();
	endif;
endif;
