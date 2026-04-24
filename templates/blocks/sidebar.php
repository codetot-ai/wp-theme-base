<?php
/**
 * Block: Sidebar
 *
 * @package codetot
 * @author codetot
 * @since 1.0.0
 */

$data = wp_parse_args(
	$args, [
		'class'      => '',
		'sidebar_id' => 'sidebar-1',
	]
);

$_class = 'sidebar';
$_class .= ! empty( $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';

if ( is_active_sidebar( $data['sidebar_id'] ) ) :
	?>
	<aside class="<?php echo esc_attr( $_class ); ?>" role="complementary" data-block="sidebar">
		<div class="sidebar__inner">
			<?php dynamic_sidebar( $data['sidebar_id'] ); ?>
		</div>
	</aside>
	<?php
endif;
