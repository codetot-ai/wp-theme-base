<?php
/**
 * Block: Steps Section
 *
 * @package codetot
 * @author codetot
 */

$data = wp_parse_args(
	$args, [
		'class' => '',
		'title' => '',
		'description' => '',
		'items' => [],
	]
);

$_class = 'py-2 py-lg-4 bg-light steps-section';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

if ( ! empty( $data['items'] ) ) :

	?>
	<div class="<?php echo esc_attr($_class); ?>" data-block="steps-section">
		<div class="container">
			<?php if ( ! empty( $data['title'] ) || ! empty( $data['description'] ) ) : ?>
				<div class="row mb-2">
					<div class="col-12 text-center">
						<?php if ( ! empty( $data['title'] ) ) : ?>
							<h2 class="h2 steps-section__title"><?php echo esc_html( $data['title'] ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $data['description'] ) ) : ?>
							<div class="mb-2 steps-section__description">
								<?php echo wp_kses_post( wpautop( $data['description'] ) ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="row">
				<?php
				foreach ( $data['items'] as $index => $item ) :
					$step_number = $index + 1;
					?>
					<div class="d-flex flex-column col-12 mb-2 col-md-6 col-lg-3 flex-lg-grow-1 steps-section__col">
						<div class="position-relative steps-section__item p-1 p-lg-2 bg-white d-flex flex-wrap flex-grow-1 shadow-sm">
							<span class="h2 d-flex fw-semibold text-secondary steps-section__item-step-number"><?php echo esc_html( $step_number ); ?>.</span>
							<div class="w-100 steps-section__item-content">
								<?php if ( ! empty( $item['title'] ) ) : ?>

									<h3 class="h6 fw-medium steps-section__item-title mb-1">
										<?php echo esc_html( $item['title'] ); ?>
									</h3>
								<?php endif; ?>
								<?php if ( ! empty( $item['description'] ) ) : ?>
									<div class="small steps-section__item-description">
										<?php echo wp_kses_post( wpautop( $item['description'] ) ); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
endif;
