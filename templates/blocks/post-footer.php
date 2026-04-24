<?php
/**
 * Block: Post Footer
 *
 * @package codetot
 * @author codetot
 * @since 1.0.0
 */

$data = wp_parse_args(
	$args, [
		'class'   => '',
		'post_id' => get_the_ID(),
	]
);

$_class = 'post-footer';
$_class .= ! empty( $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';

$tags             = get_the_tags( $data['post_id'] );
$author_id        = get_post_field( 'post_author', $data['post_id'] );
$author_name      = get_the_author_meta( 'display_name', $author_id );
$author_bio       = get_the_author_meta( 'description', $author_id );
$author_avatar    = get_avatar( $author_id, 80 );

?>
<div class="<?php echo esc_attr( $_class ); ?>" data-block="post-footer">
	<div class="container">
		<div class="col-lg-8 mx-auto">
			<?php if ( ! empty( $tags ) ) : ?>
				<div class="mb-2 post-footer__tags">
					<?php foreach ( $tags as $post_tag ) : ?>
						<a class="badge bg-light text-dark text-decoration-none me-1 mb-1 post-footer__tag" href="<?php echo esc_url( get_tag_link( $post_tag->term_id ) ); ?>">
							<?php echo esc_html( $post_tag->name ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $author_name ) ) : ?>
				<div class="d-flex align-items-start p-2 bg-light rounded-3 post-footer__bio">
					<?php if ( ! empty( $author_avatar ) ) : ?>
						<div class="me-2 flex-shrink-0 post-footer__avatar">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_avatar() returns safe HTML.
							echo $author_avatar;
							?>
						</div>
					<?php endif; ?>
					<div class="post-footer__bio-content">
						<p class="fw-semibold m-0 post-footer__author-name"><?php echo esc_html( $author_name ); ?></p>
						<?php if ( ! empty( $author_bio ) ) : ?>
							<p class="text-muted m-0 mt-1 post-footer__author-description"><?php echo esc_html( $author_bio ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
