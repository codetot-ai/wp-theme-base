<?php
/**
 * Block: Hero Title
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args(
	$args, [
		'class' => '',
		'title' => '',
	]
);

$_class = 'hero-image';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

if ( ! empty( $data['title'] ) ) :
	?>
	<div class="<?php echo esc_attr($_class); ?>">
		<div class="row g-0 flex-lg-row-reverse hero-image__wrapper">
			<div class="col-12 col-lg-4 col-xl-6 d-flex hero-image__col hero-image__col--media">
				<?php
				get_template_part(
					'templates/core-blocks/image', null, [
						'class' => 'w-100 ratio ratio-16x9 image--cover hero-image__image',
						'image_lazyload' => false,
						'image_size' => $data['image_size'] ?? 'large',
						'image_id' => $data['image'],
					]
				);
				?>
			</div>
			<div class="col-12 mt-lg-0 col-lg-8 col-xl-6 d-lg-flex align-items-center hero-image__col hero-image__col--content">
				<div class="p-2 p-lg-4 hero-image__content">
					<div class="d-block hero-image__block">
						<h1 class="h1 m-0 hero-image__title"><?php echo esc_html($data['title']); ?></h1>
						<?php if ( ! empty( $data['description'] ) ) : ?>
							<div class="h6 mt-2 fw-normal hero-image__description">
								<?php echo wp_kses_post( wpautop( $data['description'] ) ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
endif;
