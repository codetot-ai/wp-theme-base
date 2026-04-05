<?php
/**
 * Block: Footer
 *
 * @package codetot
 * @author codetot
 * @since 1.0.0
 */

?>

<footer class="py-2 py-lg-4 bg-light footer">
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-6">
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'footer-menu',
						'menu_class'     => 'list-unstyled d-block m-0 p-0 footer__menu',
						'container'      => false,
					]
				);
				?>
			</div>
			<div class="col-12 col-md-6 text-md-end">
				<p class="mb-0 small footer__copyright">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
				</p>
			</div>
		</div>
	</div>
</footer>
