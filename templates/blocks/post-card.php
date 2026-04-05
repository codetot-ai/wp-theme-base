<?php
/**
 * Block: Post Card
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'post_id' => null,
]);

$_class = 'position-relative bg-white shadow-lg rounded-4 overflow-hidden post-card';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

$post_title = get_the_title( $data['post_id'] );
$post_url = get_permalink( $data['post_id'] );
$post_thumbnail_id = get_post_thumbnail_id( $data['post_id'] );

?>

<div class="<?php echo esc_attr($_class); ?>">
	<?php
	get_template_part('templates/core-blocks/image', null, [
		'class' => 'ratio ratio-16x9 image--cover post-card__image',
		'image_id' => $post_thumbnail_id,
		'image_size' => 'medium_large',
	]);
	?>
	<div class="p-1 px-lg-2 d-flex flex-column flex-grow-1 justify-content-center post-card__content">
		<h2 class="h6 m-0 post-card__title text-center mb-0"><?php echo esc_html( $post_title ); ?></h2>
	</div>
	<a class="position-absolute top-0 start-0 w-100 h-100 z-1 post-card__overlay-link" href="<?php echo esc_url_raw( $post_url ); ?>" ></a>
</div>


