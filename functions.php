<?php
/**
 * @package BuddyBoss Child
 * The parent theme functions are located at /buddyboss-theme/inc/theme/functions.php
 * Add your own functions at the bottom of this file.
 */

/****************************** THEME SETUP ******************************/

/**
 * Sets up theme for translation
 *s
 * @since BuddyBoss Child 1.0.0
 */
function buddyboss_theme_child_languages()
{
    /**
     * Makes child theme available for translation.
     * Translations can be added into the /languages/ directory.
     */

    // Translate text from the PARENT theme.
    load_theme_textdomain('buddyboss-theme', get_stylesheet_directory() . '/languages');

    // Translate text from the CHILD theme only.
    // Change 'buddyboss-theme' instances in all child theme files to 'buddyboss-theme-child'.
    // load_theme_textdomain( 'buddyboss-theme-child', get_stylesheet_directory() . '/languages' );

}
add_action('after_setup_theme', 'buddyboss_theme_child_languages');

/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function buddyboss_theme_child_scripts_styles()
{
    /**
     * Scripts and Styles loaded by the parent theme can be unloaded if needed
     * using wp_deregister_script or wp_deregister_style.
     *
     * See the WordPress Codex for more information about those functions:
     * http://codex.wordpress.org/Function_Reference/wp_deregister_script
     * http://codex.wordpress.org/Function_Reference/wp_deregister_style
     **/

    // Styles
    wp_enqueue_style('buddyboss-child-css', get_stylesheet_directory_uri() . '/assets/css/custom.css', array(), time());
    wp_enqueue_style('buddyboss-child-jquery-datatable-css', get_stylesheet_directory_uri() . '/assets/css/jquery.dataTables.min.css');

    wp_enqueue_script('moment-js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js');

    // Javascript


    wp_enqueue_script('buddyboss-child-jquery-datatable-js', get_stylesheet_directory_uri() . '/assets/js/dataTable/jquery.dataTables.min.js', array('jquery'));
    wp_enqueue_script('buddyboss-child-dataTables-buttons-js', get_stylesheet_directory_uri() . '/assets/js/dataTable/dataTables.buttons.min.js', array('jquery', 'buddyboss-child-jquery-datatable-js'));
    wp_enqueue_script('buddyboss-child-jszip-js', get_stylesheet_directory_uri() . '/assets/js/dataTable/jszip.min.js', array('jquery', 'buddyboss-child-jquery-datatable-js'));
    wp_enqueue_script('buddyboss-child-buttons-html5-js', get_stylesheet_directory_uri() . '/assets/js/dataTable/buttons.html5.min.js', array('jquery', 'buddyboss-child-jquery-datatable-js'));
    wp_enqueue_script('buddyboss-child-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery', 'moment-js', 'buddyboss-child-jquery-datatable-js', 'buddyboss-child-dataTables-buttons-js', 'buddyboss-child-buttons-html5-js'), time());
    wp_localize_script(
        'buddyboss-child-js',
        'globalMemberPortal',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'site_url' => site_url(),
        )
    );

}
add_action('wp_enqueue_scripts', 'buddyboss_theme_child_scripts_styles', 9999);

/****************************** CUSTOM FUNCTIONS ******************************/

define('paymentSync_INCLUDES', trailingslashit(__DIR__ . '/paymentSyncGoogleSheet'));

require_once paymentSync_INCLUDES . 'main.php';


define('MentorAPI_INCLUDES', trailingslashit(__DIR__ . '/mentor'));
require_once MentorAPI_INCLUDES . 'main.php';