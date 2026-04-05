<?php
/**
 * Block: Intro Content
 *
 * @package codetot
 * @author codetot
 */

$data = wp_parse_args(
	$args, [
		'class' => '',
		'title' => '',
		'items' => [],
	]
);

$_class = 'py-3 py-lg-5 bg-light section--lg faq';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

if ( ! empty( $data['items'] ) ) :

	// Generate schema.org FAQ structured data
	$schema_faq = [
		'@context' => 'https://schema.org',
		'@type' => 'FAQPage',
		'mainEntity' => [],
	];

	?>
	<div class="<?php echo esc_attr($_class); ?>" data-block="faq">
		<div class="container">
			<h2 class="h2 faq__title"><?php echo esc_html( $data['title'] ); ?></h2>
			<div class="faq__main">
				<?php
				foreach ( $data['items'] as $index => $item ) :
					$schema_faq['mainEntity'][] = [
						'@type' => 'Question',
						'name' => $item['title'],
						'acceptedAnswer' => [
							'@type' => 'Answer',
							'text' => wp_kses_post( wpautop( $item['description'] ) ),
						],
					];

					?>
					<div class="faq__item bg-white my-1 border rounded-3 shadow-sm">
						<button class="w-100 m-0 px-2 py-1 text-dark d-flex align-items-center justify-content-between fw-semibold text-start border-0 bg-transparent faq__item-button" role="tab" aria-controls="faq-answer-<?php echo esc_attr( $index ); ?>" aria-expanded="false">
							<span class="text pe-none"><?php echo esc_html( $item['title'] ); ?></span>
							<span class="icon pe-none d-inline-flex align-items-center justify-content-center"><?php echo codetot_get_svg_icon('plus'); ?></span>
						</button>
						<div class="faq__item-content d-none" aria-expanded="false" role="tabpanel" id="faq-answer-<?php echo esc_attr( $index ); ?>">
							<div class="px-1 faq__item-content-wrapper">
								<?php echo wp_kses_post( wpautop( $item['description'] ) ); ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<script type="application/ld+json">
	<?php echo wp_json_encode( $schema_faq ); ?>
</script>
	<?php
endif;
