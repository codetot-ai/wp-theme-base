<?php
/**
 * Block: Post Grid
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args(
	$args, [
		'class' => '',
		'post_ids' => [],
	]
);

if ( ! empty( $data['post_ids'] ) ) :
	$_class = 'post-grid';
	$_class .= ! empty( $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';
	?>

	<div class="<?php echo esc_attr( $_class ); ?>">
		<div class="container">
			<div class="row">
				<?php
				get_template_part(
					'templates/blocks/post-grid-loop', null, [
						'post_ids' => $data['post_ids'],
					]
				);
				?>
			</div>
		</div>
	</div>
<?php endif; ?>
