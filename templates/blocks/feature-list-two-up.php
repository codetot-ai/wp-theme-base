<?php
/**
 * Block: Feature List Two Up
 *
 * @package codeteot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args(
	$args, [
		'class' => '',
		'title' => '',
		'description' => '',
		'image' => '',
		'items' => [],
		'button_text' => '',
		'button_url' => '',
		'button_type' => 'primary',
	]
);

$_class = 'feature-list-two-up';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

?>
<div class="<?php echo esc_attr($_class); ?>">
	<div class="container">
		<div class="row">
			<div class="col-12 mb-2 col-lg-8 mb-lg-0">
				<div class="feature-list-two-up__inner">
					<div class="mb-2 mb-lg-4 feature-list-two-up__header">
						<?php if ( ! empty( $data['title'] ) ) : ?>
							<h2 class="h2 feature-list-two-up__title"><?php echo esc_html( $data['title'] ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $data['description'] ) ) : ?>
							<div class="feature-list-two-up__description">
								<?php echo wp_kses_post( wpautop( $data['description'] ) ); ?>
							</div>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $data['items'] ) && is_array( $data['items'] ) ) : ?>
						<ul class="list-unstyled feature-list-two-up__list">
							<?php foreach ( $data['items'] as $item ) : ?>
								<li class="d-flex mb-1 feature-list-two-up__item">
									<span class="h6 fw-normal w-100 pb-1 border-bottom border-gray feature-list-two-up__item-text"><?php echo esc_html( $item['name'] ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if ( ! empty( $data['button_text'] ) && ! empty( $data['button_url'] ) ) : ?>
						<div class="mt-2 feature-list-two-up__footer">
							<?php
							get_template_part(
								'templates/core-blocks/button', null, [
									'class' => 'feature-list-two-up__button',
									'type' => $data['button_type'],
									'button_text' => $data['button_text'],
									'button_url' => $data['button_url'],
								]
							);
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-12 col-lg-4">
				<?php if ( ! empty( $data['image'] ) ) : ?>
					<?php
					get_template_part(
						'templates/core-blocks/image', null, [
							'class' => 'w-100 image--default feature-list-two-up__image',
							'image_lazyload' => true,
							'image_size' => $data['image_size'] ?? 'full',
							'image_id' => $data['image'],
						]
					);
					?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
