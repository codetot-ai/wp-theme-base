<?php
/**
 * Block: Hero Title
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'badge' => '',
	'title' => '',
	'highlight_title' => '',
	'description' => '',
	'button_text' => '',
	'button_url' => '',
	'button_type' => 'primary',
]);

$_class = 'py-2 py-lg-4 hero-title';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

if ( !empty( $data['title'] ) ) :
?>
	<div class="<?php echo esc_attr($_class); ?>" data-block="hero-title">
		<div class="container">
			<div class="text-center hero-title__inner">
				<?php if ( !empty( $data['badge'] ) ) : ?>
					<div class="mb-1 hero-title__badge">
						<span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-medium hero-title__badge-text"><?php echo esc_html( $data['badge'] ); ?></span>
					</div>
				<?php endif; ?>
				<h1 class="h1 m-0 hero-title__title">
					<?php echo esc_html( $data['title'] ); ?>
					<?php if ( !empty( $data['highlight_title'] ) ) : ?>
						<span class="text-primary hero-title__highlight"><?php echo esc_html( $data['highlight_title'] ); ?></span>
					<?php endif; ?>
				</h1>
				<?php if ( !empty( $data['description'] ) ) : ?>
					<div class="mt-2 mx-auto hero-title__description hero-title__description-text">
						<?php echo wp_kses_post( wpautop( $data['description'] ) ); ?>
					</div>
				<?php endif; ?>
				<?php if ( !empty( $data['button_text'] ) && !empty( $data['button_url'] ) ) : ?>
					<div class="mt-2 hero-title__footer">
						<?php get_template_part('templates/core-blocks/button', null, [
							'class' => 'hero-title__button',
							'type' => $data['button_type'],
							'button_text' => $data['button_text'],
							'button_url' => $data['button_url'],
						]); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif;
