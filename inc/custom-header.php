<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
 *	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Material_Press
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses material_press_header_style()
 */
function material_press_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'material_press_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/images/header.jpg',
		'uploads'				 => true,
		'header-text'			 => false,
		'flex-height'            => true,
	) ) );
}
add_action( 'after_setup_theme', 'material_press_custom_header_setup' );
