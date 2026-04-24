<?php
/**
 * Block: Post Header
 *
 * @package codetot
 * @author codetot
 * @since 1.0.0
 */

$data = wp_parse_args(
	$args, [
		'class'    => '',
		'post_id'  => get_the_ID(),
	]
);

$_class = 'post-header';
$_class .= ! empty( $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';

$post_title        = get_the_title( $data['post_id'] );
$post_date         = get_the_date( 'd/m/Y', $data['post_id'] );
$post_author_name  = get_the_author();
$post_thumbnail_id = get_post_thumbnail_id( $data['post_id'] );
$categories        = get_the_category( $data['post_id'] );

?>
<div class="<?php echo esc_attr( $_class ); ?>" data-block="post-header">
	<div class="container">
		<?php if ( ! empty( $categories ) ) : ?>
			<div class="mb-1 post-header__categories">
				<?php foreach ( $categories as $category ) : ?>
					<a class="badge bg-primary text-decoration-none me-1 post-header__badge" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
						<?php echo esc_html( $category->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<h1 class="m-0 post-header__title"><?php echo esc_html( $post_title ); ?></h1>

		<div class="d-flex align-items-center mt-2 text-muted post-header__meta">
			<span class="post-header__date"><?php echo esc_html( $post_date ); ?></span>
			<?php if ( ! empty( $post_author_name ) ) : ?>
				<span class="mx-1 post-header__separator">&middot;</span>
				<span class="post-header__author"><?php echo esc_html( $post_author_name ); ?></span>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $post_thumbnail_id ) ) : ?>
			<div class="mt-2 post-header__image">
				<?php
				get_template_part(
					'templates/core-blocks/image', null, [
						'class'      => 'w-100 ratio ratio-16x9 image--cover post-header__featured',
						'image_id'   => $post_thumbnail_id,
						'image_size' => 'large',
					]
				);
				?>
			</div>
		<?php endif; ?>
	</div>
</div>
