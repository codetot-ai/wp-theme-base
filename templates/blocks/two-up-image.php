<?php
/**
 * Block: Two Up Image
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

// Parse arguments with defaults
$data = wp_parse_args(
	$args, [
		'class'          => '',
		'image_id'       => '',
		'title'          => '',
		'description'    => '',
		'image_lazyload' => false,
	]
);

$_class = 'two-up-image';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

?>
<section class="<?php echo esc_attr($_class); ?>">
	<div class="container two-up-image__container">
		<div class="row align-items-lg-center two-up-image__row">
			<div class="col-12 col-lg-6 mb-2 mb-lg-0 two-up-image__col two-up-image__col--image">
				<div class="two-up-image__image-wrapper">
					<?php
					get_template_part(
						'templates/core-blocks/image', null, [
							'class' => 'm-0 two-up-image__image ratio ratio-4x3 image--cover',
							'image_id' => $data['image_id'],
							'image_size' => 'large',
							'lazyload' => $data['image_lazyload'],
							'use_figure' => true,
						]
					);
					?>
				</div>
			</div>
			<div class="col-12 col-lg-6 two-up-image__col two-up-image__col--content">
				<div class="p-2 two-up-image__content">
					<h2 class="h2 m-0 two-up-image__title"><?php echo esc_html($data['title']); ?></h2>
					<?php if ( ! empty( $data['description'] ) ) : ?>
						<div class="mt-2 two-up-image__description">
							<?php echo wp_kses_post($data['description']); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
