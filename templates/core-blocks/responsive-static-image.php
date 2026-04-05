<?php
/**
 * Block: Responsive Static Image
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args(
	$args, [
		'class' => '',
		'static_mobile_filename' => '',
		'static_pc_filename' => '',
		'alt' => '',
		'width' => '',
		'height' => '',
		'breakpoint' => 800,
		'lazyload' => true,
		'data_speed' => '',
	]
);

// If static_pc_filename is not provided, use static_mobile_filename
if ( empty($data['static_pc_filename']) && ! empty($data['static_mobile_filename']) ) {
	$data['static_pc_filename'] = $data['static_mobile_filename'];
}

// Determine if we need responsive picture element
$needs_picture = ! empty($data['static_pc_filename']) && $data['static_pc_filename'] !== $data['static_mobile_filename'];

$_class = 'responsive-static-image d-block w-100';
$_class .= ! empty($data['class']) ? esc_attr(' ' . $data['class']) : '';

$_image_class = 'responsive-static-image__image d-block w-100 img-fluid';
$_image_class .= ! empty($data['image_class']) ? esc_attr(' ' . $data['image_class']) : '';
if ( ! empty($data['lazyload']) ) {
	$_image_class .= ' no-lazy';
}

if ( ! empty($data['static_mobile_filename']) ) :
	if ( $needs_picture ) :
		?>
		<picture class="<?php echo esc_attr($_class); ?>">
			<source
				srcset="<?php echo esc_url_raw( get_theme_file_uri('static-assets/images/' . $data['static_pc_filename']) ); ?>"
				media="(min-width: <?php echo esc_attr($data['breakpoint']); ?>px)">
			<img
				class="<?php echo esc_attr($_image_class); ?>"
				src="<?php echo esc_url_raw( get_theme_file_uri('static-assets/images/' . $data['static_mobile_filename']) ); ?>"
				alt="<?php echo ! empty($data['alt']) ? esc_attr($data['alt']) : ''; ?>"
				<?php if ( ! empty($data['width']) ) : ?> width="<?php echo esc_attr($data['width']); ?>" <?php endif; ?>
				<?php if ( ! empty($data['height']) ) : ?> height="<?php echo esc_attr($data['height']); ?>" <?php endif; ?>
				<?php if ( ! empty($data['lazyload']) ) : ?> loading="lazy" <?php endif; ?>
				<?php if ( ! empty($data['data_speed']) ) : ?> data-speed="<?php echo esc_attr($data['data_speed']); ?>" <?php endif; ?>>
		</picture>
	<?php else : ?>
		<img
			class="<?php echo esc_attr($_class . ' ' . $_image_class); ?>"
			src="<?php echo esc_url_raw( get_theme_file_uri('static-assets/images/' . $data['static_mobile_filename']) ); ?>"
			alt="<?php echo ! empty($data['alt']) ? esc_attr($data['alt']) : ''; ?>"
			<?php if ( ! empty($data['width']) ) : ?> width="<?php echo esc_attr($data['width']); ?>" <?php endif; ?>
			<?php if ( ! empty($data['height']) ) : ?> height="<?php echo esc_attr($data['height']); ?>" <?php endif; ?>
			<?php if ( ! empty($data['lazyload']) ) : ?> loading="lazy" <?php endif; ?>
			<?php if ( ! empty($data['data_speed']) ) : ?> data-speed="<?php echo esc_attr($data['data_speed']); ?>" <?php endif; ?>>
	<?php endif; ?>
<?php endif; ?>
