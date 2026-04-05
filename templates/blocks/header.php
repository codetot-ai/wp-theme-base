<?php
/**
 * Block: Header
 *
 * @package codetot
 * @author codetot
 * @since 1.0.0
 */

$_class = 'header header--dark';

?>

<header class="<?php echo esc_attr( $_class ); ?>">
	<div class="container">
		<div class="row justify-content-between">
			<div class="col-auto d-flex align-items-center header__col header__col--logo">
				<a class="d-flex align-items-center text-decoration-none header__logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php
					$logo_image_id = get_theme_mod( 'custom_logo' );

					if ( $logo_image_id ) {
						$image_html = wp_get_attachment_image(
							$logo_image_id,
							'full',
							false,
							[
								'class' => 'header__logo-image img-fluid',
								'alt'   => get_bloginfo( 'name' ),
							]
						);

						$image_html = preg_replace( '/decoding="async"/', '', $image_html );
						$image_html = str_replace( '<img ', '<img loading="eager" fetchpriority="high" ', $image_html );

						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output from wp_get_attachment_image is safe
						echo $image_html;
					} else {
						?>
						<span class="header__site-title"><?php bloginfo( 'name' ); ?></span>
						<?php
					}
					?>
				</a>
			</div>
			<div class="col-lg-9 flex-grow-1 d-none d-lg-flex align-items-center justify-content-end header__col header__col--nav">
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'primary-menu',
						'menu_class'     => 'd-inline-flex m-0 p-0 list-unstyled header__nav-menu',
						'container'      => false,
						'depth'          => 1,
					]
				);
				?>
			</div>
			<?php // Nav toggle for mobile ?>
			<div class="col-6 flex-grow-1 d-flex align-items-center justify-content-end d-lg-none">
				<button class="position-relative background-transparent border-0 p-0 m-0 d-flex flex-column justify-content-around header__nav-button" type="button" aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'codetot' ); ?>">
					<span class="icon"><?php echo codetot_get_svg_icon( 'menu', 36, 36 ); ?></span>
				</button>
			</div>
		</div>
	</div>
</header>
