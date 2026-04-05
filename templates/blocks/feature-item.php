<?php
/**
 * Block: Feature Item
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'svg_icon' => '',
	'title' => '',
	'description' => '',
	'button_type' => 'outline-primary',
	'button_text' => '',
	'button_url' => '',
]);

$_class = 'shadow-lg p-2 rounded-4 d-flex flex-column feature-item';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

$_title_class = 'feature-item__title';
$_title_class .= !empty( $data['title_class'] ) ? ' ' . esc_attr( $data['title_class'] ) : ' h4';

?>

<div class="<?php echo esc_attr($_class); ?>">
	<?php if ( ! empty( $data['svg_icon'] ) ) : ?>
		<div class="feature-item__icon mb-2">
			<div class="d-inline-flex align-items-center justify-content-center rounded-3 feature-item__icon-wrapper">
				<span class="icon d-inline-block"><?php echo codetot_get_svg_icon( $data['svg_icon'] ); ?></span>
			</div>
		</div>
	<?php endif; ?>
	<div class="d-flex flex-column flex-grow-1 feature-item__content">
		<?php if ( ! empty( $data['title'] ) ) : ?>
			<h3 class="<?php echo esc_attr($_title_class); ?>"><?php echo esc_html( $data['title'] ); ?></h3>
		<?php endif; ?>
		<?php if ( ! empty( $data['description'] ) ) : ?>
			<div class="mt-1 feature-item__description"><?php echo wp_kses_post( $data['description'] ); ?></div>
		<?php endif; ?>
		<?php if ( ! empty( $data['button_text'] ) && ! empty( $data['button_url'] ) ) : ?>
			<div class="mt-auto pt-2 feature-item__footer">
				<?php get_template_part('templates/core-blocks/button', null, [
					'class' => 'w-100 feature-item__button',
					'type' => $data['button_type'],
					'button_text' => $data['button_text'],
					'button_url' => $data['button_url'],
				]); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
