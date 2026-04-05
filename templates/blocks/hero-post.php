<?php
/**
 * Block: Hero Post
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$_class = 'bg-light hero-post';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

$post_title = get_the_title();
$post_date = get_the_date('d/m/Y');
$post_thumbnail_id = get_post_thumbnail_id();
// Generate post author avatar and name
$post_author_name = get_the_author();
$post_author_image = get_avatar_url(get_the_author_meta('ID'), ['size' => 48]);

$categories = get_the_category();
// Convert to array of name and link as item (no html)
$category_links = [];
foreach ($categories as $category) {
	$category_links[] = [
		'name' => $category->name,
		'url' => get_category_link($category->term_id)
	];
}

// Copy markup from hero-post.php and adjust to use post data
?>
<div class="<?php echo esc_attr($_class); ?>">
	<div class="row g-0 flex-lg-row-reverse hero-post__wrapper">
		<div class="col-12 col-lg-4 col-xl-6 d-flex hero-post__col hero-post__col--media">
			<?php get_template_part('templates/core-blocks/image', null, [
				'class' => 'w-100 ratio ratio-16x9 image--cover hero-post__image',
				'image_lazyload' => false,
				'image_size' => $data['image_size'] ?? 'large',
				'image_id' => $post_thumbnail_id
			]); ?>
		</div>
		<div class="col-12 mt-lg-0 col-lg-8 col-xl-6 d-lg-flex align-items-center hero-post__col hero-post__col--content">
			<div class="p-2 p-lg-4 hero-post__content">
				<div class="d-block hero-post__block">
					<?php if ( !empty( $category_links ) ) : ?>
						<ul class="list-inline mb-1 hero-post__categories">
							<?php foreach ( $category_links as $cat_link ) : ?>
								<li class="list-inline-item hero-post__category">
									<a class="fw-medium" href="<?php echo esc_url( $cat_link['url'] ); ?>">
										<?php echo esc_html( $cat_link['name'] ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<h1 class="h2 m-0 hero-post__title"><?php echo esc_html( $post_title ); ?></h1>
					<p class="text-muted small mt-1 hero-post__date">
						<span class="icon pe-none"><?php echo codetot_get_svg_icon('calendar'); ?></span>
						<span class="text"><?php echo esc_html( $post_date ); ?></span>
					</p>
					<?php
					if ( !empty( $post_author_name ) ) :
						?>
						<div class="d-flex align-items-center mt-2 hero-post__author">
							<?php if ( !empty( $post_author_image ) ) : ?>
								<div class="me-1 hero-post__author-avatar">
									<img class="rounded-circle" src="<?php echo esc_url( $post_author_image ); ?>" alt="<?php echo esc_attr( $post_author_name ); ?>" width="48" height="48">
								</div>
							<?php endif; ?>
							<div class="hero-post__author-name text-muted">
								<span class="text-muted"><?php esc_html_e('By', 'codetot'); ?></span>
								<span class="fw-medium"><?php echo esc_html( $post_author_name ); ?></span>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

