<?php
/**
 * Header Template
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'codetot' ); ?></a>
	<?php get_template_part( 'templates/blocks/header' ); ?>
	<div id="content" class="site-content">
		<section id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
