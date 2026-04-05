<?php
/**
 * CDN Support
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

if ( ! defined('CDN_DOMAIN') ) { return;
}


/**
 * Retrieve the CDN domain
 *
 * @return string|null
 */
function codetot_get_cdn_domain() {
	return defined('CDN_DOMAIN') ? CDN_DOMAIN : null;
}

/**
 * Get domain from url
 *
 * @return string
 */
function codetot_get_domain_from_url() {
	$url = get_site_url(null, '', null);

	$url_path = wp_parse_url($url);
	return $url_path['host'];
}

/**
 * Replace cdn url
 *
 * @param string $url
 * @return string
 */
function codetot_cdn_attachments_urls( $url ) {
	$cdn_domain = codetot_get_cdn_domain();

	if ( empty($cdn_domain) ) {
		return $url;
	}

	return str_replace(codetot_get_domain_from_url() . '/wp-content/uploads', $cdn_domain . '/wp-content/uploads', $url);
}

/**
 * Replace attachment srcset url
 *
 * @param array $attr
 * @return array
 */
function codetot_cdn_attachment_srcset_filter( $attr ) {
	$cdn_domain = codetot_get_cdn_domain();
	$site_domain = codetot_get_domain_from_url();

	if ( empty($cdn_domain) ) { return $attr;
	}

	if ( ! empty($attr['srcset']) ) {
		$attr_srcset = $attr['srcset'];
		$attr['srcset'] = str_replace($site_domain . '/wp-content/uploads', $cdn_domain . '/wp-content/uploads', $attr_srcset);
	}

	return $attr;
}

/**
 * Replace img srcset
 *
 * @param array $sources
 * @return array
 */
function codetot_calculate_image_srcset( $sources ) {
	$cdn_domain = codetot_get_cdn_domain();
	$site_domain = codetot_get_domain_from_url();

	if ( empty($cdn_domain) ) { return $sources;
	}

	foreach ( $sources as &$source ) {
		$source['url'] = str_replace($site_domain . '/wp-content/uploads', $cdn_domain . '/wp-content/uploads', $source['url']);
	}

	return $sources;
}

/**
 * Replace url on field acf
 *
 * @param string $value
 * @return string
 */
function codetot_acf_format_cdn_url_value( $value ) {
	$cdn_domain = codetot_get_cdn_domain();
	$site_domain = codetot_get_domain_from_url();

	if ( empty($cdn_domain) ) { return $value; }

	if ( is_array($value) ) {
		$value['url'] = str_replace($site_domain . '/wp-content/uploads', $cdn_domain . '/wp-content/uploads', $value['url']);
		if ( isset($value['sizes']) && ! empty($value['sizes']) ) {
			foreach ( $value['sizes'] as $key => $size ) {
				$value['sizes'][ $key ] = str_replace($site_domain . '/wp-content/uploads', $cdn_domain . '/wp-content/uploads', $size);
			}
		}
	} else {
		$value = str_replace($site_domain . '/wp-content/uploads', $cdn_domain . '/wp-content/uploads', $value);
	}

	return $value;
}

if ( function_exists('get_field') ) {
	add_filter('acf/format_value/type=image', 'codetot_acf_format_cdn_url_value', 10, 1);
	add_filter('acf/format_value/type=file', 'codetot_acf_format_cdn_url_value', 10, 1);
}

add_filter('wp_get_attachment_url', 'codetot_cdn_attachments_urls', 10, 1);
add_filter('wp_calculate_image_srcset', 'codetot_calculate_image_srcset');
add_filter('wp_get_attachment_image_srcset', 'codetot_cdn_attachment_srcset_filter');
