<?php
/**
 * Block: Highlight News
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */
$data = wp_parse_args($args, [
	'class' => '',
	'title' => '',
	'category_ids' => [],
	'post_ids' => [],
	'rest_post_ids' => []
]);

$_class = 'highlight-section';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

if ( !empty( $data['post_ids'] ) ) :
	$first_post_id = $data['post_ids'][0];
	$other_post_ids = array_slice($data['post_ids'], 1);
	?>
	<div class="<?php echo esc_attr($_class); ?>">
		<div class="highlight-section__main">
			<div class="container">
				<div class="row">
					<div class="col-12 mb-2 col-md-5 mb-md-0 highlight-section__col highlight-section__col--main">
						<h2 class="h2 m-0 highlight-section__title"><?php echo esc_html( $data['title'] ); ?></h2>
						<?php if ( !empty($data['category_ids'] ) ) : ?>
							<ul class="list-unstyled m-0 pt-2 highlight-section__categories">
								<?php foreach( $data['category_ids'] as $_category_id ) :
									$category_object = get_term_by('term_id', $_category_id, 'category');
									$category_name = !empty($category_object) && !is_wp_error($category_object) ? $category_object->name : '';
									?>
									<li class="mb-05 highlight-section__category-item">
										<a class="text-decoration-none d-block highlight-section__category-link" href="<?php echo esc_url_raw( get_category_link( $_category_id) ); ?>"><?php echo esc_html( $category_name ); ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
					<div class="col-12 col-md-7 highlight-section__col highlight-section__col--sidebar">
						<?php if ( !empty( $first_post_id ) ) : ?>

						<?php endif; ?>
						<?php if ( !empty( $other_post_ids ) ) : ?>
							<ul class="ps-md-2 ps-lg-4 m-0 p-0 list-unstyled highlight-section__list">
								<?php foreach($other_post_ids as $index => $other_post_id) :
									$item_class = 'highlight-section__item';
									$item_class .= $index < count($other_post_ids) - 1 ? ' mb-1 pb-1 border-bottom' : '';
									?>
									<li class="<?php echo esc_attr( $item_class ); ?>">
										<span class="d-block mb-05 opacity-75 small highlight-section__item-date"><?php echo esc_html( get_the_date('d/m/Y', $other_post_id ) ); ?></span>
										<a class="d-block fw-normal h6 text-decoration-none highlight-section__item-title" href="<?php echo esc_url_raw( get_permalink( $other_post_id ) ); ?>"><?php echo esc_html( get_the_title( $other_post_id ) ); ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<?php if ( !empty( $data['rest_post_ids'] ) ) : ?>
			<div class="highlight-section__rest">
				<div class="container">
					<div class="highlight-section__post-list">
						<?php foreach( $data['rest_post_ids'] as $rest_post_id ) :
							$categories = get_the_category( $rest_post_id );
							$category_name = !empty( $categories ) ? $categories[0]->name : '';
							?>
							<a href="<?php echo esc_url_raw( get_permalink( $rest_post_id ) ); ?>" class="highlight-section__post-row">
								<?php if ( $category_name ) : ?>
									<span class="highlight-section__post-category"><?php echo esc_html( $category_name ); ?></span>
								<?php endif; ?>
								<h3 class="highlight-section__post-title"><?php echo esc_html( get_the_title( $rest_post_id ) ); ?></h3>
								<p class="highlight-section__post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $rest_post_id ), 20, '...' ) ); ?></p>
								<span class="highlight-section__post-date"><?php echo esc_html( get_the_date( 'd/m/Y', $rest_post_id ) ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
<?php endif;
