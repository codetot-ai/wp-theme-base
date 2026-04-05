<?php
/**
 * Block: Pricing Table
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'title' => '',
	'table_header' => '',
	'table_rows' => []
]);

$_class = 'py-2 py-lg-4 section--xl pricing-table';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

if ( !empty( $data['table_header'] ) && !empty( $data['table_rows'] ) ) :

$header_lines = array_map('trim', explode("\n", $data['table_header']));

// Parse header lines: each line is "PlanName|Price|OptionalBadge"
$headers = [];
foreach ( $header_lines as $line ) {
	$parts = array_map('trim', explode('|', $line));
	$headers[] = [
		'name'    => $parts[0] ?? '',
		'price'   => $parts[1] ?? '',
		'badge'   => $parts[2] ?? '',
	];
}

// Detect which columns are "popular" (have a badge like "Pho bien nhat" or "Pho bien")
$popular_indices = [];
foreach ( $headers as $i => $header ) {
	if ( ! empty( $header['badge'] ) ) {
		$popular_indices[] = $i;
	}
}

?>
	<div class="<?php echo esc_attr($_class); ?>" data-block="pricing-table">
		<div class="container">
			<?php if ( ! empty( $data['title'] ) ) : ?>
				<div class="mb-2 pricing-table__header">
					<h2 class="h2 m-0 pricing-table__title text-center"><?php echo esc_html( $data['title'] ); ?></h2>
				</div>
			<?php endif; ?>
			<div class="pricing-table__wrapper table-responsive">
				<table class="table pricing-table__table">
					<thead>
						<tr>
							<?php foreach ( $headers as $col_index => $header ) :
								$is_label = ( $col_index === 0 );
								$is_popular = in_array( $col_index, $popular_indices, true );
								$th_class = 'p-1 align-middle pricing-table__th';
								$th_class .= $is_label ? ' pricing-table__th--label text-start' : ' text-center';
								$th_class .= $is_popular ? ' is-popular' : '';
								?>
								<th class="<?php echo esc_attr( $th_class ); ?>">
									<?php if ( $is_label ) : ?>
										<span class="d-block fw-semibold"><?php echo esc_html( $header['name'] ); ?></span>
									<?php else : ?>
										<strong class="d-block pricing-table__plan-name"><?php echo esc_html( $header['name'] ); ?></strong>
										<?php if ( ! empty( $header['price'] ) ) : ?>
											<span class="d-block pricing-table__plan-price"><?php echo esc_html( $header['price'] ); ?></span>
										<?php endif; ?>
										<?php if ( ! empty( $header['badge'] ) ) : ?>
											<span class="pricing-table__plan-badge"><?php echo esc_html( $header['badge'] ); ?></span>
										<?php endif; ?>
									<?php endif; ?>
								</th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $data['table_rows'] as $row ) :
							if ( empty( $row['table_content'] ) ) continue;
							$row_cells = array_map('trim', explode('|', $row['table_content']));
							$row_url = $row['url'] ?? '';
							?>
							<tr>
								<?php foreach ( $row_cells as $index => $cell ) :
									$is_feature_label = ( $index === 0 );
									$td_class = 'p-1 align-middle pricing-table__td';
									$td_class .= $is_feature_label ? ' text-start fw-medium' : ' text-center';
									$td_class .= in_array( $index, $popular_indices, true ) ? ' is-popular' : '';
									?>
									<td class="<?php echo esc_attr( $td_class ); ?>">
										<?php
										if ( $cell === 'Có' || $cell === 'Co' ) :
											?><span class="pricing-table__check" aria-label="<?php esc_attr_e( 'Có', 'codetot' ); ?>">&#10003;</span><?php
										elseif ( $cell === '—' || $cell === '-' ) :
											?><span class="pricing-table__dash" aria-label="<?php esc_attr_e( 'Không', 'codetot' ); ?>">&mdash;</span><?php
										else :
											echo esc_html( $cell );
										endif;
										?>
									</td>
								<?php endforeach; ?>
								<?php if ( ! empty( $row_url ) ) : ?>
									<td class="p-1 text-center align-middle pricing-table__td">
										<a href="<?php echo esc_url( $row_url ); ?>" class="btn btn-sm btn-primary">
											<?php esc_html_e( 'Xem chi tiết', 'codetot' ); ?>
										</a>
									</td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php endif;
