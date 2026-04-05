<?php
/**
 * Block: Listing Section
 *
 * @package codetot
 * @author codetot
 * @since 1.0.0
 */

$data = wp_parse_args(
	$args, [
		'class' => '',
		'title' => '',
		'description' => '',
		'items' => [],
	]
);

$_class = 'py-2 py-lg-4 section--lg listing-section';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

if ( ! empty( $data['items'] ) ) :
	?>
	<div class="<?php echo esc_attr($_class); ?>" data-block="listing-section">
		<div class="container">
			<div class="mb-2 listing-section__header">
				<h2 class="h2 m-0 listing-section__title"><?php echo esc_html($data['title']); ?></h2>
				<?php if ( ! empty( $data['description'] ) ) : ?>
					<div class="h6 mt-2 fw-normal listing-section__description">
						<?php echo wp_kses_post( wpautop( $data['description'] ) ); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="listing-section__main">
				<?php foreach ( $data['items'] as $item ) : ?>
					<div class="listing-section__item">
						<span class="listing-section__checkbox">
							<span class="listing-section__checkbox-box"></span>
							<span class="listing-section__checkbox-check">
								<?php echo codetot_get_svg_icon('check', 48, 48); ?>
							</span>
						</span>
						<span class="listing-section__item-title"><?php echo esc_html($item['title']); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
endif;
