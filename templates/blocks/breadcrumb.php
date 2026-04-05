<?php
/**
 * Block: Breadcrumb
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$data = wp_parse_args(
	$args,
	[
		'class' => '',
	]
);

$_class = 'breadcrumb';
$_class .= ! empty($data['class']) ? esc_attr(' ' . $data['class']) : '';

if ( function_exists('rank_math_the_breadcrumbs') ) :
?>
	<div class="<?php echo esc_attr($_class); ?>">
		<div class="container">
			<div class="row">
				<div class="breadcrumb__wrapper d-flex align-items-center justify-content-center justify-content-md-start text-black col-12 col-md-11 offset-md-1">
					<?php rank_math_the_breadcrumbs(); ?>
				</div>
			</div>
		</div>
	</div>
<?php endif;