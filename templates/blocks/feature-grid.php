<?php
/**
 * Block: Feature Grid
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args(
	$args, [
		'class' => '',
		'title' => '',
		'items' => [],
	]
);

$_class = 'feature-grid';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

$_item_count = count($data['items']);
$_col_lg = 4 === $_item_count ? 3 : ( 2 === $_item_count ? 6 : 4 );

?>
<div class="<?php echo esc_attr($_class); ?>">
	<div class="container">
		<?php if ( ! empty( $data['title'] ) ) : ?>
			<div class="mb-2 feature-grid__header">
				<h2 class="h2 m-0 feature-grid__title text-center"><?php echo esc_html( $data['title'] ); ?></h2>
			</div>
		<?php endif; ?>
		<div class="row">
			<?php foreach ( $data['items'] as $item ) : ?>
				<div class="d-flex col-6 col-lg-<?php echo esc_attr($_col_lg); ?> mb-2">
					<?php
					get_template_part(
						'templates/blocks/feature-item', null, [
							'class' => 'feature-grid__item mb-4 mb-md-0',
							'title_class' => 'h3',
							'svg_icon' => $item['svg_icon'] ?? '',
							'title' => $item['title'] ?? '',
							'description' => $item['description'] ?? '',
							'button_type' => 'outline-primary',
							'button_text' => $item['button_text'] ?? '',
							'button_url' => $item['button_url'] ?? '',
						]
					);
					?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
