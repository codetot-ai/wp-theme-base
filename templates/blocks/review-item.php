<?php
/**
 * Block: Review
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'name' => '',
	'date' => '',
	'image_id' => null,
	'quote' => '',
]);

?>
<div class="review-item">
	<div class="review-item__inner">
		<?php if ( ! empty( $data['image_id'] ) ) : ?>
			<div class="review-item__avatar">
				<?php echo wp_get_attachment_image( $data['image_id'], 'thumbnail', false, [
					'class' => 'review-item__avatar-img',
					'loading' => 'lazy',
				]); ?>
			</div>
		<?php endif; ?>
		<div class="review-item__content">
			<div class="review-item__meta">
				<span class="review-item__name"><?php echo esc_html( $data['name'] ); ?></span>
			</div>
			<?php if ( ! empty( $data['date'] ) ) : ?>
				<p class="review-item__date"><?php echo esc_html( $data['date'] ); ?></p>
			<?php endif; ?>
			<div class="review-item__stars">
				<?php for ( $i = 0; $i < 5; $i++ ) : ?>
					<span class="review-item__star">
						<?php echo codetot_get_svg_icon( 'star' ); ?>
					</span>
				<?php endfor; ?>
			</div>
			<div class="review-item__quote">
				<?php echo wp_kses_post( wpautop( $data['quote'] ) ); ?>
			</div>
		</div>
	</div>
</div>
