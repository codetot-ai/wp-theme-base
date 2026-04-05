<?php
/**
 * Block: Logo Grid
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args(
	$args, [
		'class' => '',
		'title' => '',
		'description' => '',
		'items' => [],
	]
);

$_class = 'py-2 py-lg-4 section--lg logo-grid';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

if ( ! empty( $data['items'] ) ) :

	?>
	<div class="<?php echo esc_attr($_class); ?>">
		<div class="container">
			<?php if ( ! empty( $data['title'] ) || ! empty( $data['description'] ) ) : ?>
				<div class="mb-2 logo-grid__header text-center">
					<?php if ( ! empty( $data['title'] ) ) : ?>
						<h2 class="h2 m-0 logo-grid__title"><?php echo esc_html( $data['title'] ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $data['description'] ) ) : ?>
						<div class="mt-1 logo-grid__description">
							<?php echo wp_kses_post( wpautop( $data['description'] ) ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="row align-items-center justify-content-center logo-grid__main">
				<?php
				foreach ( $data['items'] as $item ) :
					$image_id = $item['image'] ?? '';
					$url = $item['url'] ?? '';
					if ( empty( $image_id ) ) { continue;
					}
					?>
					<div class="col-4 col-md-3 col-lg-2 mb-2 text-center logo-grid__col">
						<?php if ( ! empty( $url ) ) : ?>
							<a href="<?php echo esc_url( $url ); ?>" class="d-inline-block logo-grid__link" target="_blank" rel="noopener noreferrer">
						<?php endif; ?>
						<?php
						get_template_part(
							'templates/core-blocks/image', null, [
								'image_id' => $image_id,
								'size' => 'medium',
								'class' => 'logo-grid__image',
							]
						);
						?>
						<?php if ( ! empty( $url ) ) : ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
endif;
