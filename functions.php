<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

define('ACC_MAJOR_RELEASE', '0');
define('ACC_MINOR_RELEASE', '1');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyten_setup() in a child theme, add your own twentyten_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails, custom headers and backgrounds, and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
	add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'twentyten', get_template_directory() . '/languages' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'twentyten' ),
	) );

	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', array(
		// Let WordPress know what our default background color is.
		'default-color' => 'f1f1f1',
	) );

	// The custom header business starts here.

	$custom_header_support = array(
		// The default image to use.
		// The %s is a placeholder for the theme template directory URI.
		'default-image' => get_bloginfo('stylesheet_directory') . '/images/ApexCougarClubBanner_v4.png',
		// The height and width of our custom header.
		'width' => apply_filters( 'twentyten_header_image_width', 940 ),
		'height' => apply_filters( 'twentyten_header_image_height', 198 ),
		// Support flexible heights.
		'flex-height' => false,
		// Don't support text inside the header image.
		'header-text' => false,
		// Callback for styling the header preview in the admin.
		'admin-head-callback' => 'twentyten_admin_header_style',
	);

	add_theme_support( 'custom-header', $custom_header_support );

	if ( ! function_exists( 'get_custom_header' ) ) {
		// This is all for compatibility with versions of WordPress prior to 3.4.
		define( 'HEADER_TEXTCOLOR', '' );
		define( 'NO_HEADER_TEXT', true );
		define( 'HEADER_IMAGE', $custom_header_support['default-image'] );
		define( 'HEADER_IMAGE_WIDTH', $custom_header_support['width'] );
		define( 'HEADER_IMAGE_HEIGHT', $custom_header_support['height'] );
		add_custom_image_header( '', $custom_header_support['admin-head-callback'] );
		add_custom_background();
	}

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be 940 pixels wide by 198 pixels tall.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( $custom_header_support['width'], $custom_header_support['height'], true );

	// ... and thus ends the custom header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'v1' => array(
			'url' => get_bloginfo('stylesheet_directory') . '/images/ApexCougarClubBanner_v1.png',
			'thumbnail_url' => get_bloginfo('stylesheet_directory') . '/images/ApexCougarClubBanner_v1-thumbnail.png',
			/* translators: header image description */
			'description' => __( 'Apex Cougar Club v1', 'twentyten' )
		),
		'v2' => array(
			'url' => get_bloginfo('stylesheet_directory') . '/images/ApexCougarClubBanner_v2.png',
			'thumbnail_url' => get_bloginfo('stylesheet_directory') . '/images/ApexCougarClubBanner_v2-thumbnail.png',
			/* translators: header image description */
			'description' => __( 'Apex Cougar Club v2', 'twentyten' )
        ),
  		'v3' => array(
			'url' => get_bloginfo('stylesheet_directory') . '/images/ApexCougarClubBanner_v3.png',
			'thumbnail_url' => get_bloginfo('stylesheet_directory') . '/images/ApexCougarClubBanner_v3-thumbnail.png',
			/* translators: header image description */
			'description' => __( 'Apex Cougar Club v3', 'twentyten' )
        ),
  		'v4' => array(
			'url' => get_bloginfo('stylesheet_directory') . '/images/ApexCougarClubBanner_v4.png',
			'thumbnail_url' => get_bloginfo('stylesheet_directory') . '/images/ApexCougarClubBanner_v4-thumbnail.png',
			/* translators: header image description */
			'description' => __( 'Apex Cougar Club v4', 'twentyten' )
		)
	) );
}

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyten_setup().
 *
 * @since Twenty Ten 1.0
 */
function twentyten_admin_header_style() {
?>
<style type="text/css">
/* Shows the same border as on front end */
#headimg {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
	#headimg #name { }
	#headimg #desc { }
*/
</style>
<?php
}

/**
 * acc_wp_head()
 *
 */
function acc_wp_head() {
    printf('<meta name="viewport" content="width=device-width" />%s', PHP_EOL) ;
	printf('<link rel="shortcut icon" href="%s/images/favicon.ico" >%s', get_stylesheet_directory_uri(), PHP_EOL) ;

    //  TwentyTen-ACC needs jQuery!
    wp_enqueue_script('jquery') ;
    
    //  Load Chosen jQuery plugin to handle dropdown menus on mobile devices

    wp_register_script( 'acc-chosen',
        sprintf('%s/js/chosen/chosen.jquery.min.js', get_stylesheet_directory_uri()), array('jquery'));

    wp_enqueue_script('acc-chosen') ;

    wp_enqueue_style('acc-chosen-css',
        sprintf('%s/js/chosen/chosen.css', get_stylesheet_directory_uri())) ;
}
add_action('wp_head', 'acc_wp_head');

/**
 * twentyten_acc_wp_footer()
 *
 * By default TwentyTen doesn't have an easy way to add the mobile menu
 * block we want nor is there any obvious hooks to use.  Instead of copy
 * and modifying one of TwentyTen template files (which means updates would
 * never be incorporated automatically), we'll use the standard WordPress
 * wp_footer action to construct a DIV element for the mobile menu block.
 * The DIV for the mobile menu block are hidden initially.  When the page
 * loads, a jQuery script will "move" the block to its proper location and
 * then make it visible.
 *
 */
function twentyten_acc_wp_footer()
{
?>
<div id="mobile-menus"><?php //dropdown_menu( array(
		//'theme_location' => 'primary',
    	//'dropdown_title' => '-- Main Menu --',
    	//'indent_string' => '- ',
    	//'indent_after' => ''
	//) ); ?></div>

<!--  Move the DIV to its proper location -->
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#mobile-menus').appendTo($('#masthead'));

        // Create the dropdown base
        $("<select />").appendTo("#mobile-menus");
        //$("#masthead select").attr("id", "mobile-menus");
        $("#mobile-menus select").addClass("chzn-select");
        $("#mobile-menus select").attr("data-placeholder", "Go to ...");

        // Create default option "Go to..."
        $("<option />", {
            //"selected": "selected",
            "value"   : "",
            "text"    : ""
        }).appendTo("#mobile-menus select");

        // Populate dropdown with menu items
        $("#access div.menu-header a").each(function() {
            var el = $(this);
            $("<option />", {
            "value"   : el.attr("href"),
            "text"    : el.text()
            }).appendTo("#mobile-menus select");
        });

        // To make dropdown actually work
        // To make more unobtrusive: http://css-tricks.com/4064-unobtrusive-page-changer/
        $("#mobile-menus select").change(function() {
            window.location = $(this).find("option:selected").val();
        });
        $(".chzn-select").chosen({
            disable_search: true,
            width: "100%"
        }) ;
    }) ;

</script>
    
<?php
}

add_action('wp_footer', 'twentyten_acc_wp_footer');

/**
 * Action to add theme credits
 *
 */
function acc_credits()
{
    $txt = sprintf('<div id="acc-footer">Copyright &copy; 2012-%s, %s - All rights reserved.<br>',
        date('Y'), get_bloginfo('name')) ;
    $txt .= sprintf('The <a href="http://michaelwalsh.org/wordpress-themes/twentyten-acc/" title="TwentyTen-ACC">TwentyTen-ACC</a> theme (v%s.%s) is a child theme of the <a href="http://theme.wordpress.com/themes/twentyten/">Twenty Ten</a> theme.</div>', ACC_MAJOR_RELEASE, ACC_MINOR_RELEASE) ;

    echo $txt ;
}
add_action('twentyten_credits', 'acc_credits') ;

//  Load AdRotate customizations
require_once(dirname( __FILE__ ) . '/xtra/adrotate.php');

//  Load WooCommerce customizations
require_once(dirname( __FILE__ ) . '/xtra/woocommerce.php');

?>
