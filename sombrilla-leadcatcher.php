<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codean.do/
 * @since             1.0.0
 * @package           Sombrilla_Leadcatcher
 *
 * @wordpress-plugin
 * Plugin Name:       Sombrilla LeadCatcher
 * Plugin URI:        https://codean.do/custom
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Codean.Do
 * Author URI:        https://codean.do/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sombrilla-leadcatcher
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PLUGIN_NAME_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sombrilla-leadcatcher-activator.php
 */
function activate_sombrilla_leadcatcher()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-sombrilla-leadcatcher-activator.php';
    Sombrilla_Leadcatcher_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sombrilla-leadcatcher-deactivator.php
 */
function deactivate_sombrilla_leadcatcher()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-sombrilla-leadcatcher-deactivator.php';
    Sombrilla_Leadcatcher_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_sombrilla_leadcatcher');
register_deactivation_hook(__FILE__, 'deactivate_sombrilla_leadcatcher');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-sombrilla-leadcatcher.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function filter_et_shortcode_att($data)
{



    GLOBAL $slc;
    $userid   = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
    $userdata = get_userdata($userid);
    $usermeta = get_user_meta($userid);

    $ytid      = (isset($usermeta['ytid'][0])) ? $usermeta['ytid'][0] : get_user_meta(1)['ytid'][0];
    $facebook  = (isset($usermeta['facebook'][0])) ? $usermeta['facebook'][0] : '';
    $twitter   = (isset($usermeta['twitter'][0])) ? $usermeta['twitter'][0] : '';
    $gplus     = (isset($usermeta['gplus'][0])) ? $usermeta['gplus'][0] : '';
    $skype     = (isset($usermeta['skype'][0])) ? $usermeta['skype'][0] : '';
    $youtube   = (isset($usermeta['youtube'][0])) ? $usermeta['youtube'][0] : '';
    $instagram = (isset($usermeta['instagram'][0])) ? $usermeta['instagram'][0] : '';
    $linkedin  = (isset($usermeta['linkedin'][0])) ? $usermeta['linkedin'][0] : '';
    $ws        = (isset($usermeta['ws'][0])) ? $usermeta['ws'][0] : '';
    $pp        = (isset($usermeta['profile-picture'][0])) ? $usermeta['profile-picture'][0] : '';

    $search = [
        '#slc-username#',
        '#slc-email#',
        '#slc-firstname#',
        '#slc-lastname#',
        '#slc-ytid#',
        '#slc-facebook#',
        '#slc-twitter#',
        '#slc-gplus#',
        '#slc-skype#',
        '#slc-bio#',
        '#slc-youtube#',
        '#slc-instagram#',
        '#slc-linkedin#',
        '#slc-ws#',
        '#slc-pp#'
    ];

    $replace = [
        $userdata->user_login,
        $userdata->user_email,
        $usermeta['first_name'][0],
        $usermeta['last_name'][0],
        $ytid,
        $facebook,
        $twitter,
        $gplus,
        $skype,
        $usermeta['description'][0],
        $youtube,
        $instagram,
        $linkedin,
        $ws,
        $pp
    ];

    foreach ($slc->fields as $field) {
        $search[] = '#slc-' .$field['id'].'#';
        $replace[] = $usermeta[$field['id']][0];
    }

    foreach ($data as $key => $value) {

        if (preg_match('/#/', $value)) {
            $data[$key] = str_replace($search, $replace, $value);
        }

    }



    return $data;

}



function subdomain_init()
{
    global $slc;
    add_filter('bloginfo_url', ['Sombrilla_Leadcatcher_Wildcard', 'replaceDomainBySub']);
    add_filter('home_url', ['Sombrilla_Leadcatcher_Wildcard', 'replaceDomainBySub']);
   
    add_action('stylesheet_directory_uri', ['Sombrilla_Leadcatcher_Wildcard', 'replaceDomainBySub']);

    //ShortCodes
    add_shortcode('slc-username', ['Sombrilla_Leadcatcher_Shortcodes', 'get_username']);
    add_shortcode('slc-email', ['Sombrilla_Leadcatcher_Shortcodes', 'get_email']);
    add_shortcode('slc-firstname', ['Sombrilla_Leadcatcher_Shortcodes', 'get_firstname']);
    add_shortcode('slc-lastname', ['Sombrilla_Leadcatcher_Shortcodes', 'get_lastname']);
    add_shortcode('slc-ytid', ['Sombrilla_Leadcatcher_Shortcodes', 'get_ytid']);
    add_shortcode('slc-facebook', ['Sombrilla_Leadcatcher_Shortcodes', 'get_facebook']);
    add_shortcode('slc-twitter', ['Sombrilla_Leadcatcher_Shortcodes', 'get_twitter']);
    add_shortcode('slc-gplus', ['Sombrilla_Leadcatcher_Shortcodes', 'get_gplus']);
    add_shortcode('slc-skype', ['Sombrilla_Leadcatcher_Shortcodes', 'get_skype']);
    add_shortcode('slc-bio', ['Sombrilla_Leadcatcher_Shortcodes', 'get_bio']);
    add_shortcode('slc-youtube', ['Sombrilla_Leadcatcher_Shortcodes', 'get_youtube']);
    add_shortcode('slc-linkedin', ['Sombrilla_Leadcatcher_Shortcodes', 'get_linkedin']);
    add_shortcode('slc-instagram', ['Sombrilla_Leadcatcher_Shortcodes', 'get_instagram']);
    add_shortcode('slc-ws', ['Sombrilla_Leadcatcher_Shortcodes', 'get_ws']);
    add_shortcode('slc-pp', ['Sombrilla_Leadcatcher_Shortcodes', 'get_pp']);

    foreach ($slc->fields as $field) {
       add_shortcode('slc-'.$field['id'], function() use( &$field){
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta[$field['id']][0])) ? $usermeta[$field['id']][0]:'';
 
       });

    }
	
	if(isset($_GET['logout'])){
	wp_logout();
}

}

function run_sombrilla_leadcatcher()
{

    $plugin = new Sombrilla_Leadcatcher();
    $plugin->run();

   
    if (!isset($_GET['et_fb'])) {
 add_action('et_pb_module_shortcode_attributes', 'filter_et_shortcode_att');
    }


     

   
    add_filter('user_contactmethods', ['Sombrilla_Leadcatcher_Public', 'addNewUserFields']);
    add_shortcode('slc-registration-form', ['Sombrilla_Leadcatcher_Public', 'showRegistrationForm']);
    add_shortcode('slc-login-form', ['Sombrilla_Leadcatcher_Public', 'showLoginForm']);
    add_shortcode('slc-edit-profile-form', ['Sombrilla_Leadcatcher_Public', 'showEditProfileForm']);
    add_action('init', 'subdomain_init');
    add_action('init', ['Sombrilla_Leadcatcher_Public', 'registerUser']);
    add_filter('option_whatsappme', ['Sombrilla_Leadcatcher_Shortcodes', 'get_option_ws']);
}

run_sombrilla_leadcatcher();
add_filter('site_url', function ($url){

    
    if (is_admin()) {
       return $url;
    }

    $path = (isset(parse_url($url)['path'])) ? parse_url($url)['path']:'';
    return 'https://'.$_SERVER['HTTP_HOST'].$path;

});


$slc = new Sombrilla_Leadcatcher_Shortcodes();

$slc->addNewField('ws-message', 'Mensaje de Whatsapp');

