<?php
/**
 * MaterialPress functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Material_Press
 */

if ( ! function_exists( 'wp_cached_script_enqueue' ) ) {
	define("CACHE_BUSTER", "080318-1533303942");
	function wp_cached_enqueue( ?string $type = 'style', string $cache_string = '', string $handle, string $src, array $deps = [] ) {
		if ( true === empty($cache_string) ) {
			$cache_string = CACHE_BUSTER;
		}
		if ( false === is_null($cache_string) ) {
			$src = $src . '?cache=' . $cache_string;
		}
		if ('script' === $type) {
			return wp_enqueue_script( $handle, $src, $deps, null );
		}
		return wp_enqueue_style( $handle, $src, $deps, null );
	}
}

if ( ! function_exists( 'material_press_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function material_press_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on MaterialPress, use a find and replace
		 * to change 'material_press' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'material_press', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		// register_nav_menus( array(
		// 	'menu-1' => esc_html__( 'Primary', 'material_press' ),
		// ) );
		add_theme_support('menus');

		register_nav_menu('primary', 'Primary Drawer Navigation');
		register_nav_menu('secondary', 'Footer Navigation');
		register_nav_menu('menutopright', 'Top right menu Navigation');
		register_nav_menu('tabmenu', 'Tabs Navigation');

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'material_press_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'material_press_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function material_press_content_width() {
	// This variable is intended to be overruled from themes.
	$content_width = $GLOBALS['content_width'] ?? 640;

	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'material_press_content_width', $content_width );
}
add_action( 'after_setup_theme', 'material_press_content_width', 0 );

/**
 * Add more allowed HTML Tags to the Nav Menu Container.
 *
 * @param array $tags The acceptable HTML tags for use as menu containers.
 *                    Default is array containing 'div' and 'nav'.
 *
 * @link https://developer.wordpress.org/reference/hooks/wp_nav_menu_container_allowedtags/
 */
function material_press_add_allowed_nav_tags( $tags ) {
	$tags[] = 'section';
	return $tags;
}
add_filter( 'wp_nav_menu_container_allowedtags', 'material_press_add_allowed_nav_tags' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function material_press_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'material_press' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'material_press' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer', 'good_company' ),
		'id'=>'footer-1',
		'class'=>'footer-area',
		'description' => esc_html__( 'Add footer widgets here.', 'material_press' ),
		'before_widget'=>'<section id="%1$s" class="widget %2$s mdc-footer__widget">',
		'after_widget'=>'</section>',
		'before_title'=>'<h2 class="mdc-typography--headline">',
		'after_title'=>'</h2>'
	) );
}
add_action( 'widgets_init', 'material_press_widgets_init' );


/**
 * Enqueue scripts and styles.
 */
function material_press_scripts() {
	//css
	wp_enqueue_style('materialstyle', get_template_directory_uri() . '/css/material-components-web.min.css', array(), '0.24.0', 'all');
	wp_enqueue_style('materialicons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), '1.0', 'all');
	wp_enqueue_style('robotofont', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500', array(), '1.0', 'all');
	wp_enqueue_style('customstyles', get_template_directory_uri() . '/css/custom_styles.css', array(), '1.0', 'all');
	//js
	wp_enqueue_script('materialjs', get_template_directory_uri() . '/js/material-components-web.min.js', array(), '0.24.0', true);
	wp_enqueue_script('customscripts', get_template_directory_uri() . '/js/custom_scripts.js', array(), '1.0', true);
	echo('<meta name="viewport" content="width=device-width,initial-scale=1">');

}
add_action( 'wp_enqueue_scripts', 'material_press_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_theme_file_path( '/inc/extras.php' );

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

// Include Nav Walkers
require get_template_directory() . '/inc/drawer-walker.php';
require get_template_directory() . '/inc/tab-walker.php';
require get_template_directory() . '/inc/menu-walker.php';

/*
	==========================================
	  Style author name
	==========================================
*/
add_filter( 'the_author_posts_link', function( $link ) {
    return str_replace( 'rel="author"', 'rel="author" class="mdcwp-meta__anchor"', $link );
});

/*
	==========================================
	  Show posts in chronological order
	==========================================
*/
function reverse_post_order_pre_get_posts( $query ) {
	if ( ! is_admin() && $query->is_main_query() ) {
		$query->set( 'order', 'ASC' );
	}
}
add_filter( 'pre_get_posts', 'reverse_post_order_pre_get_posts' );

/*
	==========================================
	  Show only posts in search
	==========================================
*/
function SearchFilter($query) {
	if ($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}
add_filter('pre_get_posts','SearchFilter');

/*
	==========================================
	  Material Design read more
	==========================================
*/
function modify_read_more_link() {
	return '<br><br><a class="mdc-button mdc-button--raised mdc-button--primary" href="' . get_permalink() . '">Read more</a>';
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );

/*
	==========================================
	  Head function
	==========================================
*/
function material_press_remove_version() {
	return '';
}
add_filter('the_generator', 'material_press_remove_version');

/*
	==========================================
	  Comments callback
	==========================================
*/
function material_press_comment($comment, $args, $depth) {
	global $comment;

    if ($comment->user_id == '0') {
        if (!empty ($comment->comment_author_url)) {
            $url = $comment->comment_author_url;
        } else {
            $url = '#';
        }
    } else {
        $url = get_author_posts_url($comment->user_id);
    }

    if ( 'div' === $args['style'] ) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }
    ?>

	<hr class="mdc-list-divider">

	<br>

	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">

		<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
		<?php endif; ?>

		<div style="display: flex;">
			<div>
				<div class="comment-author vcard">
					<?php if ( $args['avatar_size'] != 0 ) echo '<div style="position: absolute;background: #bdbdbd;height: 32px;width: 32px;border-radius: 50%;background-image:url(' . get_avatar_url( $comment, array('size'=>32) ) . ')"></div>';?>
					<?php printf( __( '<h1 class="mdc-card__title mdcwp-comment__title mdc-theme--primary" style="margin-left: 46px;"><a href="' . $url . '" class="mdc-theme--primary" rel="external nofollow">' . get_comment_author() .'</a></h1>' ), get_comment_author_link() ); ?>
				</div>
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata" style="display: flex;"><a style="text-decoration: none;" href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php printf( __('<h2 class="mdc-card__subtitle" style="margin-left: 46px;">%1$s at %2$s</h2>'), get_comment_date(),  get_comment_time() ); ?></a>
				</div>
			</div>
			<?php edit_comment_link( __( '<div class="mdc-ripple-surface material-icons" data-mdc-ripple-is-unbounded aria-label="Favorite" tabindex="0">edit</div>' ), '<span style="margin-left: 20px; margin-top: 10px;">', '' ); ?>
		</div>

		<?php comment_text(); ?>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'], 'before'=>'<button class="mdc-button mdc-button--compact">', 'after'=>'</button>') ) ); ?>
		</div>

		<?php if ( 'div' != $args['style'] ) : ?>

    </div>

	<br>

    <?php endif; ?>
<?php
}

/*
	==========================================
	  Show meta data
	==========================================
*/
function material_press_add_custom_properties() {
	add_meta_box( 'mdcwp_custom_properties', 'Custom Properties', 'mdcwp_custom_properties_callback', null, 'side' );
}
function mdcwp_custom_properties_callback( $post ) {
	wp_nonce_field( 'mdcwp_save_custom_properties', 'mdcwp_custom_properties_meta_box_nonce' );

	$hide_author_value = get_post_meta( $post->ID, '_mdcwp_metadata_value_key', true );
	$custom_button_text_value = get_post_meta( $post->ID, '_mdcwp_custom_button_text_value_key', true );
	$immersive_mode_value = get_post_meta( $post->ID, '_mdcwp_immersive_mode_value_key', true );

	echo '<div><input type="checkbox" ' . (($hide_author_value) ? 'checked ' : "") . 'name="author_hidden" id="author_hidden" /> ';
	echo '<label for="author_hidden">Hide author</label></div>';
	echo '<br>';
	echo '<div><label for="author_hidden">Custom button text</label><br>';
	echo '<input type="text" value="' . $custom_button_text_value . '" name="custom_button_text" id="custom_button_text" placeholder="Button on the card\'s bottom left side." style="width: 100%;" /></div>';
	echo '<br>';
	echo '<div><input type="checkbox" ' . (($immersive_mode_value) ? 'checked ' : "") . 'name="immersive_mode" id="immersive_mode" /> ';
	echo '<label for="immersive_mode">Immersive mode (good for long articles)</label></div>';

}
function material_press_save_custom_properties( $post_id ) {

	if (isset($_POST['author_hidden'])) {
        update_post_meta($post_id, '_mdcwp_metadata_value_key', $_POST['author_hidden']);
    } else {
        delete_post_meta($post_id, '_mdcwp_metadata_value_key');
    }

	if (isset($_POST['custom_button_text'])) {
        update_post_meta($post_id, '_mdcwp_custom_button_text_value_key', sanitize_text_field($_POST['custom_button_text']));
    } else {
        delete_post_meta($post_id, '_mdcwp_custom_button_text_value_key');
    }

	if (isset($_POST['immersive_mode'])) {
        update_post_meta($post_id, '_mdcwp_immersive_mode_value_key', sanitize_text_field($_POST['immersive_mode']));
    } else {
        delete_post_meta($post_id, '_mdcwp_immersive_mode_value_key');
    }

}
add_action( 'add_meta_boxes', 'material_press_add_custom_properties' );
add_action( 'save_post', 'material_press_save_custom_properties' );



/*
	==========================================
	  Custom icon menu field
	==========================================
*/

// Create field
function fields_list() {
	return array(
		'field_icon' => 'Material Design Icon'
	);
}

// Setup fields
function material_press_fields( $id, $item, $depth, $args ) {
	$fields = fields_list();

	foreach( $fields as $_key => $label ) :

		$key = sprintf( 'menu-item-%s', $_key );
		$id = sprintf( 'edit-%s-%s', $key, $item->ID );
		$name = sprintf( '%s[%s]', $key, $item->ID );
		$value = get_post_meta( $item->ID, $key, true );
		$class = sprintf( 'field-%s', $_key );

		?>

		<p class="description description-wide <?php echo esc_attr( $class ) ?>">
			<label for="<?php echo esc_attr( $id ) ?>"><?php echo esc_attr( $label ); ?><br><input type="text" id="<?php echo esc_attr($id) ?>" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>"></label>
		</p>

		<?php

	endforeach;
}

add_action( 'wp_nav_menu_item_custom_fields', 'material_press_fields', 10, 4 );

// Show columns
function material_press_columns( $columns ) {
	$fields = fields_list();

	$columns = array_merge( $columns, $fields );

	return $columns;
}
add_filter( 'manage_nav-menus_columns', 'material_press_columns', 99 );

// Save fields
function material_press_nav_menu_item( $menu_id, $menu_item_db_id, $menu_item_args ) {
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}
	check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );
	$fields = fields_list();
	foreach ( $fields as $_key => $label ) {
		$key = sprintf( 'menu-item-%s', $_key );
		// Sanitize.
		if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
			// Do some checks here...
			$value = $_POST[ $key ][ $menu_item_db_id ];
		} else {
			$value = null;
		}
		// Update.
		if ( ! is_null( $value ) ) {
			update_post_meta( $menu_item_db_id, $key, $value );
			echo "key:$key<br />";
		} else {
			delete_post_meta( $menu_item_db_id, $key );
		}
	}
}
add_action( 'wp_update_nav_menu_item', 'material_press_nav_menu_item', 10, 3 );

function material_press_filter_walker( $walker ) {
    $walker = 'MDCWP_Walker_Edit';
    if ( ! class_exists( $walker ) ) {
        require_once dirname( __FILE__ ) . '/inc/walker-nav-menu-edit.php';
    }
    return $walker;
}
add_filter( 'wp_edit_nav_menu_walker', 'material_press_filter_walker', 99 );
