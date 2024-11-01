<?php
/*
Plugin Name: XMLRPC Lockdown
Plugin URI: https://wordpress.org/plugins/xmlrpc-lockdown/
Description: Disables xmlrpc.php on your WordPress Website except for allowed services like JetPack, WordPress mobile application, and other specified services.
Version: 1.1
Author: Adam Langley - AO Digital
Author URI: https://aodigital.com.au
Tested up to: WordPress 6.4.2
Requires PHP: 7.4
*/

add_filter('xmlrpc_enabled', 'disable_xmlrpc_except_for_allowed_services');

function disable_xmlrpc_except_for_allowed_services($enabled) {
    $allowed_services = ['Jetpack', 'wp-iphone', 'AdditionalService1', 'AdditionalService2']; // Add more services as needed

    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        foreach ($allowed_services as $service) {
            if (strpos($_SERVER['HTTP_USER_AGENT'], $service) !== false) {
                return $enabled;
            }
        }
    }

    return false;
}

// Optional: Add admin menu for settings page
add_action('admin_menu', 'xmlrpc_lockdown_menu');

function xmlrpc_lockdown_menu() {
    add_options_page('XMLRPC Lockdown Settings', 'XMLRPC Lockdown', 'manage_options', 'xmlrpc-lockdown', 'xmlrpc_lockdown_settings_page');
}

// Optional: Settings page content
function xmlrpc_lockdown_settings_page() {
    ?>
    <div class="wrap">
        <h2>XMLRPC Lockdown Settings</h2>
        <form action="options.php" method="post">
            <?php settings_fields('xmlrpc_lockdown_options'); ?>
            <?php do_settings_sections('xmlrpc-lockdown'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Allowed Services</th>
                    <td><input type="text" name="allowed_services" value="<?php echo get_option('allowed_services'); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Optional: Register settings
add_action('admin_init', 'xmlrpc_lockdown_admin_init');

function xmlrpc_lockdown_admin_init() {
    register_setting('xmlrpc_lockdown_options', 'allowed_services');
}