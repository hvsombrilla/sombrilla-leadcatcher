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
class Sombrilla_Leadcatcher_Wildcard
{

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

    public static function replaceDomainBySub($url)
    {

        $aff = self::getUserIDByWildCard();

        if ($aff != 1) {
            $affuser = get_userdata($aff)->user_login;
            $url     = str_replace('://', '://' . $affuser . '.', $url);
        }

        return $url;
    }

    public static function getUserIDByWildCard()
    {
        $hostdomain = explode('.', $_SERVER['HTTP_HOST']);

        if (count($hostdomain) == 3) {
            if (isset(get_user_by('login', $hostdomain[0])->ID)) {
                return get_user_by('login', $hostdomain[0])->ID;
            }
        }

        return 1;
    }

}
