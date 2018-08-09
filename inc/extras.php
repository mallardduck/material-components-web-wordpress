<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Good_Company
 */

 /**
  * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
  *
  * @param array $args Configuration arguments.
  * @return array
  */
 function material_press_page_menu_args($args)
 {
     $args['show_home'] = true;
     return $args;
 }
 add_filter('wp_page_menu_args', 'material_press_page_menu_args');

 /**
  * Handles JavaScript detection.
  *
  * Adds a `js` class to the root `<html>` element when JavaScript is detected.
  *
  * @since Twenty Seventeen 1.0
  */
 function material_press_javascript_detection() {
 	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js' )})(document.documentElement);</script>\n";
 }
 add_action( 'wp_head', 'material_press_javascript_detection', 0 );

 /**
  * Filters the script and style loader source; removes the ?ver chache buster.
  *
  * @param string $src    Script loader source path.
  */
 function material_press_remove_script_version( $src ) {
 	$parts = explode( '?ver', $src );
 	return $parts[0];
 }
 add_filter( 'script_loader_src', 'material_press_remove_script_version', 15 );
 add_filter( 'style_loader_src', 'material_press_remove_script_version', 15 );

 /**
  *  Allow HTML in author bio section.
  */
 remove_filter( 'pre_user_description', 'wp_filter_kses' );

 /**
  *  Remove WP-Generator Meta tag.
  */
 remove_action( 'wp_head', 'wp_generator' );

 /**
  *  Remove generator from our feeds too.
  */
 remove_action( 'rss2_head', 'the_generator' );
 remove_action( 'rss_head',  'the_generator' );
 remove_action( 'rdf_header', 'the_generator' );
 remove_action( 'atom_head', 'the_generator' );
 remove_action( 'commentsrss2_head', 'the_generator' );
 remove_action( 'opml_head', 'the_generator' );
 remove_action( 'app_head',  'the_generator' );
 remove_action( 'comments_atom_head', 'the_generator' );

 /**
  * Remove XML-RPC EditURI & windows live writer
  */
 add_action( 'wp', function () {
 	remove_action( 'wp_head', 'rsd_link' );
 	remove_action( 'wp_head', 'wlwmanifest_link' );
 }, 9);

 /**
  * Remove WP Emoji!
  */
 function material_press_kill_wp_emoji() {
 	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
 	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
 	remove_action( 'wp_print_styles', 'print_emoji_styles' );
 	remove_action( 'admin_print_styles', 'print_emoji_styles' );
 	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
 	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
 	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
 	add_filter( 'emoji_svg_url', '__return_false' );
 }
 add_action( 'init', 'material_press_kill_wp_emoji' );

 /**
  * Pretty/Nice search functions; setup a redirect from old search to pretty search.
  */
 function material_press_nice_search_redirect() {
 	global $wp_rewrite;
 	if ( ! isset( $wp_rewrite ) || ! is_object( $wp_rewrite ) || ! $wp_rewrite->using_permalinks() ) {
 		return;
 	}

 	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
 		$request_uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
 		$search_base = $wp_rewrite->search_base;
 		if ( is_search() && ! is_admin() && strpos( $request_uri, "/{$search_base}/" ) === false && strpos( $request_uri, '&' ) === false ) {
 			wp_safe_redirect( get_search_link(), 301 );
 			exit();
 		}
 	} else {
 		wp_safe_redirect( get_home_url(), 301 );
 		exit();
 	}
 }
 add_action( 'template_redirect', 'material_press_nice_search_redirect' );

 /**
  * Pretty/Nice search functions; enable /search/ urls for searching.
  *
  * @param string $search_url The search URL for this site with a `{search_term_string}` variable.
  */
 function material_press_get_search_rewrite( $search_url ) {
 	return str_replace( '/?s=', '/search/', $search_url );
 }
 add_filter( 'wpseo_json_ld_search_url', 'material_press_get_search_rewrite' );

 /**
  * Add preconnect for external resources.
  *
  * @param array  $hints           URLs to print for resource hints.
  * @param string $relation_type  The relation type the URLs are printed.
  * @return array $urls           URLs to print for resource hints.
  */
 function material_press_resource_hints( $urls, $relation_type ) {
 	if ( 'dns-prefetch' === $relation_type ) {
        $dns_prefetch = [
            '//fonts.googleapis.com',
            '//fonts.gstatic.com',
     		// '//www.facebook.com',
     		// '//connect.facebook.net',
     		// '//staticxx.facebook.com',
     		// '//apis.google.com',
     		// '//www.google-analytics.com',
     		// '//www.googletagmanager.com',
     		// '//cdnjs.cloudflare.com',
     	];
 		foreach ($dns_prefetch as $domain) {
 			$urls[] = array(
 				'href' => $domain,
 			);
 		}
 	} elseif ( 'preconnect' === $relation_type ) {
        $preconnect = [
            '//fonts.googleapis.com',
            '//fonts.gstatic.com',
     		// '//www.facebook.com',
     		// '//connect.facebook.net',
     		// '//apis.google.com',
     		// '//cdnjs.cloudflare.com',
     	];
 		foreach ($preconnect as $domain) {
 			$urls[] = array(
 				'href' => $domain,
 				'crossorigin'
 			);
 		}
 	}
 	return $urls;
 }
 add_filter( 'wp_resource_hints', 'material_press_resource_hints', 10, 2 );


 /**
  * Adds additional template details when in debug mode.
  */
if (WP_DEBUG === true) {
  add_filter( 'template_include', 'material_press_var_template_include', 1000 );
  function material_press_var_template_include( $t ){
    $GLOBALS['current_theme_template'] = basename($t);
    return $t;
  }

  function get_current_template( $echo = false ) {
    if( !isset( $GLOBALS['current_theme_template'] ) ) {
      return false;
    }
    if( $echo ) {
      echo $GLOBALS['current_theme_template'];
      return;
    }
    return $GLOBALS['current_theme_template'];
  }
} else {
  function get_current_template( $echo = false ) {
    return;
  }
}
