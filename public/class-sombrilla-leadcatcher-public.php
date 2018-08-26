<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codean.do/
 * @since      1.0.0
 *
 * @package    Sombrilla_Leadcatcher
 * @subpackage Sombrilla_Leadcatcher/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sombrilla_Leadcatcher
 * @subpackage Sombrilla_Leadcatcher/public
 * @author     Codean.Do <info@codean.do>
 */
class Sombrilla_Leadcatcher_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Sombrilla_Leadcatcher_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Sombrilla_Leadcatcher_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, home_url().'/wp-content/plugins/sombrilla-leadcatcher/public/css/sombrilla-leadcatcher-public.css', array(), $this->version, 'all');
        wp_enqueue_style('bootstrap_css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Sombrilla_Leadcatcher_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Sombrilla_Leadcatcher_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/sombrilla-leadcatcher-public.js', array('jquery'), $this->version, false);

    }

    public static function sombrilla_lc_errors()
    {
        static $wp_error; // Will hold global variable safely
        return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
    }

    public static function addNewUserFields($profile_fields)
    {

        GLOBAL $slc;
        // Add new fields
         $profile_fields['profile-picture']      = 'Imagen de Perfil';
        $profile_fields['twitter']      = 'Twitter Username';
        $profile_fields['facebook']     = 'Facebook URL';
        $profile_fields['gplus']        = 'Google+ URL';
        $profile_fields['skype2']       = 'Skype';
        $profile_fields['youtube']      = 'Canal de Youtube';
        $profile_fields['instagram']    = 'Instagram';
        $profile_fields['ws']           = 'Link de WS';
        $profile_fields['linkedin']     = 'Linkedin';
        $profile_fields['registro_url'] = 'Url de registro';
        $profile_fields['ytid']         = 'URL de Video';

        foreach ($slc->fields as $field) {
            $profile_fields[$field['id']]         = $field['label'];
        }
        unset($profile_fields['web']);

        $profile_fields['country'] = 'Pais';

        return $profile_fields;

    }

    public static function showLoginForm()
    {
        ob_start();

        if (!is_user_logged_in()) {?>

		<div class="row">
			<div class="col-md-4 offset-md-4">
				<?php wp_login_form(['redirect' => home_url() . $_SERVER['REQUEST_URI']]);?>
			</div>
		</div>

   	<?php
}
        return ob_get_clean();
    }

    public static function registerUser()
    {
        // && wp_verify_nonce($_POST['sombrilla_lc_register_nonce'], 'sombrilla-lc-register-nonce')
        if (isset($_POST["sombrilla_lc_user_login"])) {
            $user_login   = $_POST["sombrilla_lc_user_login"];
            $user_email   = $_POST["sombrilla_lc_user_email"];
            $user_first   = $_POST["sombrilla_lc_user_first"];
            $user_last    = $_POST["sombrilla_lc_user_last"];
            $user_pass    = $_POST["sombrilla_lc_user_pass"];
            $pass_confirm = $_POST["sombrilla_lc_user_pass_confirm"];

            // this is required for username checks
            require_once ABSPATH . WPINC . '/registration.php';

            if (username_exists($user_login)) {
                // Username already registered
                self::sombrilla_lc_errors()->add('username_unavailable', __('Username already taken'));
            }
            if (!validate_username($user_login)) {
                // invalid username
                self::sombrilla_lc_errors()->add('username_invalid', __('Invalid username'));
            }
            if ($user_login == '') {
                // empty username
                self::sombrilla_lc_errors()->add('username_empty', __('Please enter a username'));
            }
            if (!is_email($user_email)) {
                //invalid email
                self::sombrilla_lc_errors()->add('email_invalid', __('Invalid email'));
            }
            if (email_exists($user_email)) {
                //Email address already registered
                self::sombrilla_lc_errors()->add('email_used', __('Email already registered'));
            }
            if ($user_pass == '') {
                // passwords do not match
                self::sombrilla_lc_errors()->add('password_empty', __('Please enter a password'));
            }
            if ($user_pass != $pass_confirm) {
                // passwords do not match
                self::sombrilla_lc_errors()->add('password_mismatch', __('Passwords do not match'));
            }

            $errors = self::sombrilla_lc_errors()->get_error_messages();

            // only create the user in if there are no errors
            if (empty($errors)) {

                $new_user_id = wp_insert_user(array(
                    'user_login'      => $user_login,
                    'user_pass'       => $user_pass,
                    'user_email'      => $user_email,
                    'first_name'      => $user_first,
                    'last_name'       => $user_last,
                    'user_registered' => date('Y-m-d H:i:s'),
                    'role'            => 'subscriber',
                )
                );
                if ($new_user_id) {
                    // send an email to the admin alerting them of the registration
                    wp_new_user_notification($new_user_id);

                    // log the new user in
                    wp_setcookie($user_login, $user_pass, true);
                    //        wm_wp_new_user_notification($new_user_id, false, 'both');

   

                    wp_set_current_user($new_user_id, $user_login);
                    do_action('wp_login', $user_login);

                    update_user_meta($new_user_id, 'country', $_POST['country']);

                    // send the newly created user to the home page after logging them in
                    wp_redirect(home_url());
                    exit;
                }

            }

        }
    }

    public static function sombrilla_lc_show_error_messages()
    {
        if ($codes = self::sombrilla_lc_errors()->get_error_codes()) {
            echo '<div class="pippin_errors">';
            // Loop error codes and display errors
            foreach ($codes as $code) {
                $message = self::pippin_errors()->get_error_message($code);
                echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
            }
            echo '</div>';
        }
    }

    public static function showRegistrationForm()
    {

        if (!is_user_logged_in()) {
            $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

            ob_start();

            self::sombrilla_lc_show_error_messages();?>

		<form id="sombrilla_leadcatcher_registration_form" class="sombrilla_lc_form" action="" method="POST">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="sombrilla_lc_user_Login"><?php _e('Username:');?></label>
						<input name="sombrilla_lc_user_login" id="sombrilla_lc_user_login" class="form-control" type="text"/>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="sombrilla_lc_user_email"><?php _e('Email:');?></label>
						<input name="sombrilla_lc_user_email" id="sombrilla_lc_user_email"  class="form-control" type="email"/>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="sombrilla_lc_user_first"><?php _e('Name:');?></label>
						<input name="sombrilla_lc_user_first" id="sombrilla_lc_user_first"  class="form-control" type="text"/>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="sombrilla_lc_user_last"><?php _e('Last Name:');?></label>
						<input name="sombrilla_lc_user_last" id="sombrilla_lc_user_last"  class="form-control" type="text"/>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="sombrilla_lc_user_pass"><?php _e('Password:');?></label>
						<input name="sombrilla_lc_user_pass" id="sombrilla_lc_user_pass"  class="form-control" type="password"/>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="sombrilla_lc_user_pass_confirm"><?php _e('Confirm Password:');?></label>
						<input name="sombrilla_lc_user_pass_confirm" id="sombrilla_lc_user_pass_confirm"  class="form-control" type="password"/>
					</div>
				</div>


				<div class="col-md-6">
					<div class="form-group">
						<label for="sombrilla_lc_user_country"><?php _e('Country:');?></label>
						<select name="sombrilla_lc_user_country" id="sombrilla_lc_user_country" class="form-control">
							<?php foreach ($countries as $pais) {?>
								<option value="<?php echo $pais ?>"><?php echo $pais ?></option>
							<?php	}?>
						</select>
					</div>
				</div>

			</div>

			<div>
				<input type="hidden" name="sombrilla_lc_register_nonce" value="<?php echo wp_create_nonce('sombrilla_lc-register-nonce'); ?>"/>
					<input type="submit" value="<?php _e('Register Now!');?>" class='btn btn-primary' />
			</div>
		</form>
	<?php
return ob_get_clean();
        }

    }

    public static function showEditProfileForm()
    {

        GLOBAL $slc;
        if (is_user_logged_in()) {
            global $current_user, $wp_roles;

            //get_currentuserinfo(); //deprecated since 3.1

            /* Load the registration file. */
            //require_once( ABSPATH . WPINC . '/registration.php' ); //deprecated since 3.1
            $error = array();
            /* If profile was saved, update profile. */
            if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'update-user') {

                /* Update user password. */
                if (!empty($_POST['pass1']) && !empty($_POST['pass2'])) {
                    if ($_POST['pass1'] == $_POST['pass2']) {
                        wp_update_user(array('ID' => get_current_user_id(), 'user_pass' => esc_attr($_POST['pass1'])));
                    } else {
                        $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
                    }

                }

                /* Update user information. */
                if (!empty($_POST['url'])) {
                    wp_update_user(array('ID' => get_current_user_id(), 'user_url' => esc_url($_POST['url'])));
                }

                if (!empty($_POST['email'])) {
                    if (!is_email(esc_attr($_POST['email']))) {
                        $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
                    } elseif (email_exists(esc_attr($_POST['email'])) != get_current_user_id()) {
                        $error[] = __('This email is already used by another user.  try a different one.', 'profile');
                    } else {
                        wp_update_user(array('ID' => get_current_user_id(), 'user_email' => esc_attr($_POST['email'])));
                    }
                }

if (empty($_FILES['profile-picture']['name'])) {
$upload = wp_handle_upload($_FILES['profile-picture'], array( 'test_form' => false ));
update_user_meta(get_current_user_id(), 'profile-picture', $upload['url']);
}
                update_user_meta(get_current_user_id(), 'facebook', $_POST['facebook']);
                update_user_meta(get_current_user_id(), 'twitter', $_POST['twitter']);
                update_user_meta(get_current_user_id(), 'gplus', $_POST['gplus']);
                update_user_meta(get_current_user_id(), 'ytid', $_POST['ytid']);
                update_user_meta(get_current_user_id(), 'skype', $_POST['skype']);
                update_user_meta(get_current_user_id(), 'youtube', $_POST['youtube']);
                update_user_meta(get_current_user_id(), 'instagram', $_POST['instagram']);
                update_user_meta(get_current_user_id(), 'ws', $_POST['ws']);

                foreach ($slc->fields as $field) {
                   update_user_meta(get_current_user_id(), $field['id'], $_POST[$field['id']]);
                 
                }
                update_user_meta(get_current_user_id(), 'linkedin', $_POST['linkedin']);

                if (!empty($_POST['first-name'])) {
                    update_user_meta(get_current_user_id(), 'first_name', esc_attr($_POST['first-name']));
                }

                if (!empty($_POST['last-name'])) {
                    update_user_meta(get_current_user_id(), 'last_name', esc_attr($_POST['last-name']));
                }

                if (!empty($_POST['description'])) {
                    update_user_meta(get_current_user_id(), 'description', esc_attr($_POST['description']));
                }

                /* Redirect so the page will show updated info.*/
                /*I am not Author of this Code- i dont know why but it worked for me after changing below line to if ( count($error) == 0 ){ */
                if (count($error) == 0) {
                    do_action('edit_user_profile_update', get_current_user_id());
                }
            }

            $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

            ob_start();

            // sombrilla_lc_show_error_messages(); ?>
<?php if (!is_user_logged_in()): ?>
                    <p class="warning">
                        <?php _e('You must be logged in to edit your profile.', 'sombrilla-leadcatcher');?>
                    </p><!-- .warning -->
            <?php else: ?>
                <?php if (count($error) > 0) {
                echo '<p class="error">' . implode("<br />", $error) . '</p>';
            }
            ?>

                <?php
            $userdata = get_userdata(get_current_user_id());
            $usermeta = get_user_meta(get_current_user_id());

            ?>


                <form method="post" enctype="multipart/form-data" id="adduser" action="<?php the_permalink();?>">

<?php if (!empty($usermeta['profile-picture'][0])): ?>
  <div class="text-center">
    <img src="<?php echo $usermeta['profile-picture'][0]; ?>" alt="" width="200px">
</div>
  
<?php endif ?>


                	<div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="profile-picture"><?php _e('Profile Picture:', 'sombrilla-leadcatcher');?></label>
                                <input class="text-input form-control" value='' name="profile-picture" type="file" id="profile-picture"/>
                            </div>
                        </div>


                		<div class="col-md-6">
                			<div class="form-group">
                				<label for="username"><?php _e('Username:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" name="username" type="text" id="username" value="<?php echo $userdata->user_login; ?>" disabled />
                			</div>
                		</div>
                	</div>

                	<div class="row">
                		<div class="col-md-6">
                			<div class="form-group">
                				<label for="fname"><?php _e('First Name:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" name="first-name" type="text" id="first-name" value="<?php echo $userdata->first_name; ?>"  />
                			</div>
                		</div>

                		<div class="col-md-6">
                			<div class="form-group">
                				<label for="last-name"><?php _e('Last Name:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" name="last-name" type="text" id="last-name" value="<?php echo $userdata->last_name; ?>"  />
                			</div>
                		</div>
					</div>
					<div class="row">
                		<div class="col-md-6">
                			<div class="form-group">
                				<label for="email"><?php _e('E-mail:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" name="email" type="text" id="email" value="<?php echo $userdata->user_email; ?>" required />
                			</div>
                		</div>
					</div>

					<div class="row">
                		<div class="col-md-6">
                			<div class="form-group">
                				<label for="pass1"><?php _e('Password:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" name="pass1" type="password" id="pass1"/>
                			</div>
                		</div>

                		<div class="col-md-6">
                			<div class="form-group">
                				<label for="pass2"><?php _e('Retype Password:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" name="pass2" type="password" id="pass2"/>
                			</div>
                		</div>

                		<div class="col-md-12">
                			<div class="form-group">
                				<label for="description"><?php _e('Bio:', 'sombrilla-leadcatcher');?></label>
                				<textarea name="description" class="form-control" id="description" rows="3" cols="50"><?php the_author_meta('description', get_current_user_id());?></textarea>
                			</div>
                		</div>

                	</div>


                	<h2><?php _e('Contact Info', 'sombrilla-leadcatcher');?></h2>

                	<div class="row">
						<div class="col-md-6">
                			<div class="form-group">
                				<label for="ytid"><?php _e('Promo Video ID:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" value='<?php echo (isset($usermeta['ytid'][0])) ? $usermeta['ytid'][0] : ''; ?>' name="ytid" type="text" id="ytid"/>
                			</div>
                		</div>





						<div class="col-md-6">
                			<div class="form-group">
                				<label for="facebook"><?php _e('Facebook URL:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" value='<?php echo (isset($usermeta['facebook'][0])) ? $usermeta['facebook'][0] : ''; ?>' name="facebook" type="url" id="facebook"/>
                			</div>
                		</div>




						<div class="col-md-6">
                			<div class="form-group">
                				<label for="twitter"><?php _e('Twitter URL:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" value="<?php echo (isset($usermeta['twitter'][0])) ? $usermeta['twitter'][0] : ''; ?>" name="twitter" type="url" id="twitter"/>
                			</div>
                		</div>




						<div class="col-md-6">
                			<div class="form-group">
                				<label for="gplus"><?php _e('Google+ URL:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" value='<?php echo (isset($usermeta['gplus'][0])) ? $usermeta['gplus'][0] : ''; ?>' name="gplus" type="url" id="gplus"/>
                			</div>
                		</div>

						<div class="col-md-6">
                			<div class="form-group">
                				<label for="gplus"><?php _e('Youtube Chanel:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" value='<?php echo (isset($usermeta['youtube'][0])) ? $usermeta['youtube'][0] : ''; ?>' name="youtube" type="url" id="youtube"/>
                			</div>
                		</div>

                		<div class="col-md-6">
                			<div class="form-group">
                				<label for="gplus"><?php _e('Instagram URL:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" value='<?php echo (isset($usermeta['instagram'][0])) ? $usermeta['instagram'][0] : ''; ?>' name="instagram" type="url" id="instagram"/>
                			</div>
                		</div>

                		<div class="col-md-6">
                			<div class="form-group">
                				<label for="gplus"><?php _e('Linkedin URL:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" value='<?php echo (isset($usermeta['linkedin'][0])) ? $usermeta['linkedin'][0] : ''; ?>' name="linkedin" type="url" id="linkedin"/>
                			</div>
                		</div>

                		<div class="col-md-6">
                			<div class="form-group">
                				<label for="gplus"><?php _e('Whatsapp:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" value='<?php echo (isset($usermeta['ws'][0])) ? $usermeta['ws'][0] : ''; ?>' name="ws" type="text" id="ws"/>
                			</div>
                		</div>


						<div class="col-md-6">
                			<div class="form-group">
                				<label for="skype"><?php _e('Skype:', 'sombrilla-leadcatcher');?></label>
                				<input class="text-input form-control" value="<?php echo (isset($usermeta['skype'][0])) ? $usermeta['skype'][0] : ''; ?>" name="skype" type="text" id="skype"/>
                			</div>
                		</div>

                        <?php foreach ($slc->fields as $field): ?>
                                                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="<?php echo $field['id']; ?>"><?php _e($field['label'] .':', 'sombrilla-leadcatcher');?></label>
                                <input class="text-input form-control" value="<?php echo (isset($usermeta[$field['id']][0])) ? $usermeta[$field['id']][0] : ''; ?>" name="<?php  echo $field['id']; ?>" type="text" id="<?php echo $field['id']; ?>"/>
                            </div>
                        </div>
                        <?php endforeach ?>

                         
					</div>

                    <?php
//action hook for plugin and extra fields
            do_action('edit_user_profile', $current_user);
            ?>
                    <p class="form-submit">

                        <input name="updateuser" type="submit" id="updateuser" class="btn btn-primary submit et_pb_button" value="<?php _e('Update', 'sombrilla-leadcatcher');?>" />
                        <?php wp_nonce_field('update-user')?>
                        <input name="action" type="hidden" id="action" value="update-user" />
                    </p><!-- .form-submit -->
                </form><!-- #adduser -->
            <?php endif;?>

	<?php
return ob_get_clean();
        }

    }

}
