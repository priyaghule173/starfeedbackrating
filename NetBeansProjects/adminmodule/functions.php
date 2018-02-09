<?php
/**
 * Deli engine room
 *
 * @package deli
 */

/**
 * Set the theme version number as a global variable
 */
$theme				= wp_get_theme( 'deli' );
$deli_version		= $theme['Version'];

$theme				= wp_get_theme( 'storefront' );
$storefront_version	= $theme['Version'];

/**
 * Load the individual classes required by this theme
 */
require_once( 'inc/class-deli.php' );
require_once( 'inc/class-deli-customizer.php' );
require_once( 'inc/class-deli-template.php' );
require_once( 'inc/class-deli-integrations.php' );
require_once( 'inc/plugged.php' );

//Hide the wordpress toolbar for normal users
if (!current_user_can(‘edit_posts’)) {
show_admin_bar(false);
}



//Remove the designed by WooCommerce footer
add_action( 'init', 'custom_remove_footer_credit', 10 );

function custom_remove_footer_credit () {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
    add_action( 'storefront_footer', 'custom_storefront_credit', 20 );
} 

function custom_storefront_credit() {
	?>
	<div class="site-info">
		&copy; <?php echo get_bloginfo( 'name' ) . ' ' . get_the_date( 'Y' ); ?>
	</div><!-- .site-info -->
	<?php
}

function no_wordpress_errors(){
  return 'Hmm... that did not work! Try again, slowly this time...';
}
add_filter( 'login_errors', 'no_wordpress_errors' );

add_action( 'storefront_header', 'ss_add_google_fonts', 40 );
function ss_add_google_fonts() { ?>
<link href="https://fonts.googleapis.com/css?family=Anton|Montserrat|Quicksand" rel="stylesheet">
	<?php
}

function modify_logo() {
    $logo_style = '<style type="text/css">';
    $logo_style .= 'h1 a {background-image: url(' . get_stylesheet_directory_uri() . '/images/aideexpert.jpg) !important;}';
    $logo_style .= '</style>';
    echo $logo_style;
}
add_action('login_head', 'modify_logo');


function custom_login_url() {
    return 'http://www.aideexpert.com';
}
add_filter('login_headerurl', 'custom_login_url');

function custom_login_css() {
    wp_enqueue_style('login-styles', get_stylesheet_directory_uri() . '/style.css');
}
add_action('login_enqueue_scripts', 'custom_login_css');

add_action( 'init', 'jk_remove_storefront_handheld_footer_bar' );

function jk_remove_storefront_handheld_footer_bar() {
  remove_action( 'storefront_footer', 'storefront_handheld_footer_bar', 999 );
}
/**
 * Do not add custom code / snippets here.
 * While Child Themes are generally recommended for customisations, in this case it is not
 * wise. Modifying this file means that your changes will be lost when an automatic update
 * of this theme is performed. Instead, add your customisations to a plugin such as
 * https://github.com/woothemes/theme-customisations
 */
