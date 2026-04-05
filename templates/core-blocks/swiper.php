<?php
/**
 * Block: Swiper
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args(
	$args,
	[
		'class'                  => '',
		'data_block'             => '',
		'previous_svg_icon'      => 'arrow-left',
		'next_svg_icon'          => 'arrow-right',
		'settings' => [
			'autoplay' => false,
			'pagination' => true,
			'prevNextButtons' => true,
		],
		'swiper_options'         => [],
		'enable_container'       => false,
		'container_class'        => 'swiper-container',
		'items'                  => [],
	]
);

$_class  = 'js-swiper swiper';
$_class .= ! empty($data['class']) ? esc_attr(' ' . $data['class']) : '';

$_settings = $data['settings'] ?? [];

if ( ! empty($data['items']) ) :

	// Build markup
	ob_start();
	?>
	<div class="swiper-wrapper">
		<?php
		foreach ( $data['items'] as $item ) :
			$_item_class = 'swiper-slide';

			if ( ! empty($item['class']) ) :
				$_item_class .= esc_attr(' ' . $item['class']);
			endif;

			if ( ! empty($item['lazyload']) ) :
				$_item_class .= ' is-not-loaded';
			endif;
			?>
			<div class="<?php echo esc_attr($_item_class); ?>">
				<?php if ( ! empty($item['lazyload']) ) : ?>
					<noscript>
						<?php echo wp_kses_post( $item['content'] ); ?>
					</noscript>
				<?php else : ?>
					<?php echo wp_kses_post( $item['content'] ); ?>
				<?php endif; ?>
			</div><!-- Close .swiper-slide -->
		<?php endforeach; ?>
	</div><!-- Close .swiper-wrapper -->
	<?php if ( ! empty($_settings['pagination']) ) : ?>
		<div class="swiper-pagination"></div>
	<?php endif; ?>
	<?php if ( ! empty($_settings['prevNextButtons']) ) : ?>
		<div class="swiper-button swiper-button-prev position-absolute top-50 translate-middle-y d-flex rounded-pill z-2 start-0">
			<span class="icon pe-none">
				<?php echo codetot_get_svg_icon( $data['previous_svg_icon'], 44, 44 ); ?>
			</span>
		</div>
		<div class="swiper-button swiper-button-next position-absolute top-50 translate-middle-y d-flex rounded-pill z-2 end-0">
			<span class="icon pe-none">
				<?php echo codetot_get_svg_icon( $data['next_svg_icon'], 44, 44 ); ?>
			</span>
		</div>
	<?php endif; ?>
	<?php
	$slide_html = ob_get_clean();
	?>
	<div class="<?php echo esc_attr($_class); ?>" data-settings='<?php echo wp_json_encode($_settings); ?>'
		<?php if ( ! empty($data['swiper_options']) ) : ?>
		data-swiper-options='<?php echo wp_json_encode($data['swiper_options']); ?>'
		<?php endif; ?>
		<?php if ( ! empty($data['data_block']) ) : ?>
		data-block="<?php echo esc_attr($data['data_block']); ?><?php endif; ?>">
		<?php if ( ! empty($data['enable_container']) ) : ?>
			<div class="<?php echo esc_attr($data['container_class']); ?>"><?php echo $slide_html; // phpcs:ignore
			?>
																			</div><!-- Close .swiper-container -->
		<?php else : ?>
			<?php echo $slide_html; // phpcs:ignore
			?>
		<?php endif; ?>
	</div>
<?php else : ?>
	<!-- T
here are no slider items to render -->
	<?php
endif;
