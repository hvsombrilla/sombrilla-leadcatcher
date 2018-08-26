<?php

/**
 * The file that defines the wildcard functions.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://codean.do/
 * @since      1.0.0
 *
 * @package    Sombrilla_Leadcatcher
 * @subpackage Sombrilla_Leadcatcher/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sombrilla_Leadcatcher
 * @subpackage Sombrilla_Leadcatcher/includes
 * @author     Codean.Do <info@codean.do>
 */
class Sombrilla_Leadcatcher_Shortcodes
{
    public $fields = [];

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        
    }

    public static function get_username()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $userdata = get_userdata($userid);
        return $userdata->user_login;
    }

    public static function get_email()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $userdata = get_userdata($userid);
        return $userdata->user_email;
    }

    public static function get_firstname()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['first_name'][0])) ? $usermeta['first_name'][0]:'';
    }

    public static function get_lastname()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['last_name'][0])) ? $usermeta['last_name'][0]:'';
    }

    public static function get_bio()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['description'][0])) ? $usermeta['description'][0]:'';
    }

    public static function get_facebook()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['facebook'][0])) ? $usermeta['facebook'][0]:'';
    }

    public static function get_twitter()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['twitter'][0])) ? $usermeta['twitter'][0]:'';
    }

    public static function get_gplus()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['gplus'][0])) ? $usermeta['gplus'][0]:'';
    }

    public static function get_ytid()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['ytid'][0])) ? $usermeta['ytid'][0] : get_user_meta(1)['ytid'][0];
    }

    public static function get_skype()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['skype'][0])) ? $usermeta['skype'][0]:'';
    }

    public static function get_youtube()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['youtube'][0])) ? $usermeta['youtube'][0]:'';
    }

    public static function get_instagram()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['instagram'][0])) ? $usermeta['instagram'][0]:'';
    }

    public static function get_linkedin()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['linkedin'][0])) ? $usermeta['linkedin'][0]:'';
    }

    public static function get_ws()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['ws'][0])) ? $usermeta['ws'][0]:'';
    }

    public static function get_pp()
    {
        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        return (isset($usermeta['pp'][0])) ? $usermeta['pp'][0]:'';
    }

    public static function get_option_ws($array)
    {

        $userid = Sombrilla_Leadcatcher_Wildcard::getUserIDByWildCard();
        $usermeta = get_user_meta($userid);
        $ws = (isset($usermeta['ws'][0])) ? $usermeta['ws'][0]:'';
        $wstxt = (isset($usermeta['ws-message'][0])) ? $usermeta['ws-message'][0]:'';
        $array['telephone'] = $ws;
        $array['message_text'] = $wstxt;
        
        return $array;
    }

    public function addNewField($id, $label = NULL){
        $label = (is_null($label)) ? ucwords($id)  : $label;
            array_push($this->fields, ['id' => $id, 'label' => $label]); 
    }

}
