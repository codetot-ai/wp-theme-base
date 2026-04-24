<?php
/**
 * Block: Post Content
 *
 * @package codetot
 * @author codetot
 * @since 1.0.0
 */

$data = wp_parse_args(
	$args, [
		'class'         => '',
		'post_id'       => get_the_ID(),
		'content_class' => 'col-lg-8 mx-auto',
	]
);

$_class = 'post-content';
$_class .= ! empty( $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';

$content = get_post_field( 'post_content', $data['post_id'] );

if ( ! empty( $content ) ) :
	?>
	<div class="<?php echo esc_attr( $_class ); ?>" data-block="post-content">
		<div class="container">
			<div class="row justify-content-center">
				<div class="<?php echo esc_attr( $data['content_class'] ); ?>">
					<div class="post-content__body">
						<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
						echo apply_filters( 'the_content', $content );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
endif;
