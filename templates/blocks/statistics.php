<?php
/**
 * Block: Statistics
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

$_class = 'py-2 py-lg-4 section--lg statistics';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

if ( ! empty( $data['items'] ) ) :

	$col_count = count($data['items']);
	$col_class = $col_count <= 3 ? 'col-md-4' : 'col-md-6 col-lg-3';

	?>
	<div class="<?php echo esc_attr($_class); ?>" data-block="statistics">
		<div class="container">
			<?php if ( ! empty( $data['title'] ) || ! empty( $data['description'] ) ) : ?>
				<div class="row mb-2">
					<div class="col-12 text-center">
						<?php if ( ! empty( $data['title'] ) ) : ?>
							<h2 class="h2 statistics__title"><?php echo esc_html( $data['title'] ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $data['description'] ) ) : ?>
							<div class="mb-2 statistics__description">
								<?php echo wp_kses_post( wpautop( $data['description'] ) ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="row">
				<?php
				foreach ( $data['items'] as $item_index => $item ) :
						$is_last = ( $item_index === $col_count - 1 );
						$separator_class = ! $is_last ? ' statistics__col--separator' : '';
					?>
					<div class="col-6 <?php echo esc_attr($col_class . $separator_class); ?> mb-2 text-center statistics__col">
						<div class="statistics__item">
							<span class="d-block display-4 fw-bold statistics__value">
								<span class="statistics__number"><?php echo esc_html( $item['number'] ); ?></span><?php if ( ! empty( $item['unit'] ) ) : ?><span class="statistics__unit"><?php echo esc_html( $item['unit'] ); ?></span><?php endif; ?>
							</span>
							<?php if ( ! empty( $item['title'] ) ) : ?>
								<h3 class="h6 fw-medium mt-1 statistics__item-title"><?php echo esc_html( $item['title'] ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $item['description'] ) ) : ?>
								<div class="small statistics__item-description opacity-75">
									<?php echo wp_kses_post( wpautop( $item['description'] ) ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
endif;
