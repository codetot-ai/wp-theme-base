<?php
/**
 * Block: Content Area
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args(
	$args, [
		'class' => '',
		'post_id' => get_the_ID(),
		'content_class' => 'col-lg-8 mx-auto',
	]
);

$_class = 'content-area';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

?>
<div class="<?php echo esc_attr($_class); ?>">
	<div class="container">
		<div class="row justify-content-center">
			<div class="<?php echo esc_attr( $data['content_class'] ); ?>">
				<?php
				// content
				$content = get_post_field( 'post_content', $data['post_id'] );
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				echo apply_filters( 'the_content', $content );
				?>
			</div>
		</div>
	</div>
</div>
