<?php
/**
 * Material Press Theme Customizer
 *
 * @package Material_Press
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function material_press_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'material_press_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'material_press_customize_partial_blogdescription',
		) );
	}

	$wp_customize->add_section('textcolors', array('title'=>'Theme colors'));
	$txtcolors[] = array(
	    'slug'=>'primary_color_custom',
	    'default' => '#3f51b5',
	    'label' => 'Primary Color'
	);

	// secondary color ( site description, sidebar headings, h3, h5, nav links on hover )
	$txtcolors[] = array(
	    'slug'=>'accent_color_custom',
	    'default' => '#ff4081',
	    'label' => 'Accent Color'
	);

	foreach( $txtcolors as $txtcolor ) {
		// SETTINGS
		$wp_customize->add_setting( $txtcolor['slug'], array(
			'default'		=> $txtcolor['default'],
			'type'			=> 'option',
			'capability'	=>  'edit_theme_options',
		) );

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$txtcolor['slug'],
				array(
					'label'		=> $txtcolor['label'],
					'section'	=> 'textcolors',
					'settings'	=> $txtcolor['slug'],
				)
			)
		);
	}
}
add_action('customize_register', 'material_press_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function material_press_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function material_press_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

function material_press_customize_colors() {
	$primary_color_custom = get_option( 'primary_color_custom' ) ?: 'unset';
	$accent_color_custom = get_option( 'accent_color_custom' ) ?: 'unset';
	?>
	<style>
	:root {
		--mdc-theme-primary: <?php echo $primary_color_custom; ?>;
		--mdc-theme-accent: <?php echo $accent_color_custom; ?>;
	}
	</style>
	<?php
}
add_action('wp_head', 'material_press_customize_colors');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function material_press_customize_preview_js() {
	wp_enqueue_script( 'material_press-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20180809', true );
}
add_action( 'customize_preview_init', 'material_press_customize_preview_js' );
