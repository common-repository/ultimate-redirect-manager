<?php
namespace Ultimate_Solution\Ultimate;

class Ultimate_404_Menu {
    public function __construct() {
        // Register hooks
        add_action('admin_menu', array($this, 'add_ultimate_plugin_menu'));// Register hooks
        // Hook into template_redirect action to log 404 errors
        add_action('template_redirect', array($this, 'ultimate_404_logger'));
        add_action('template_redirect', array($this, 'ultimate_captured_404_url'));
        add_action('wp_ajax_delete_rule', array($this,'delete_rule_callback'));
        add_action('admin_init', array($this, 'handle_form_submission'));
    }
        
    public function handle_form_submission() {
        if ( isset( $_POST['submit'] ) ) {
            $source_url = sanitize_text_field( $_POST['source_url'] );
            $destination_url = sanitize_text_field( $_POST['destination_url'] );
            $redirect_type = sanitize_text_field( $_POST['redirect_type'] );

            // Validate and sanitize data as needed

            // Save data to the database
            global $wpdb;
            $table_name = $wpdb->prefix . 'ultimate_redirect_rules';
            $data = array(
                'source_url' => $source_url,
                'destination_url' => $destination_url,
                'redirect_type' => $redirect_type,
                'status' => 'Active',
                'type' => 'Manual', // Assuming this is a custom rule
            );
            $format = array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            );
            $wpdb->insert( $table_name, $data, $format );

        }
    }

    function ultimate_404_logger() {
        if (is_404()) {
            // Get the requested URL
            $requested_url = esc_url_raw($_SERVER['REQUEST_URI']);
            
            // Get the user IP address
            $ip_address = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
            
            // Get the user role
            $current_user = wp_get_current_user();
            $user_role = implode(', ', $current_user->roles);

            // Get the referring URL
            $reference_link = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : '';

            // Get the action (e.g., redirect, display message, etc.)
            $action = 'display'; // You can modify this based on your requirements

            // Get the current date and time
            $current_time = current_time('mysql');

            // Log the 404 error to the custom table
            global $wpdb;
            $table_name = $wpdb->prefix . 'ultimate_404_logs';
            $wpdb->insert(
                $table_name,
                array(
                    'url' => $requested_url,
                    'ip_address' => $ip_address,
                    'user_role' => $user_role,
                    'reference_link' => $reference_link,
                    'action' => $action,
                    'timestamp' => $current_time
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                )
            );
        }
    }

    function ultimate_captured_404_url() {
        if (is_404()) {
            // Get the requested URL
            $requested_url = esc_url_raw($_SERVER['REQUEST_URI']);

            // Get the current date and time
            $current_time = current_time('mysql');

            // Log the 404 error to the custom table
            global $wpdb;
            $table_name = $wpdb->prefix . 'ultimate_captured_404_url';

            // Check if the URL already exists in the table
            $existing_url = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE url = %s", $requested_url));

            if ($existing_url) {
                // If URL exists, update hits and last_used
                $hits = intval($existing_url->hits) + 1;
                $wpdb->update(
                    $table_name,
                    array(
                        'hits' => $hits,
                        'last_used' => $current_time
                    ),
                    array('id' => $existing_url->id),
                    array('%d', '%s'),
                    array('%d')
                );
            } else {
                // If URL doesn't exist, insert a new record
                $wpdb->insert(
                    $table_name,
                    array(
                        'url' => $requested_url,
                        'hits' => 1,
                        'created' => $current_time,
                        'last_used' => $current_time
                    ),
                    array(
                        '%s',
                        '%d',
                        '%s',
                        '%s'
                    )
                );
            }
        }
    }

    public function add_ultimate_plugin_menu() {
        // Add menu item under the Settings menu
        add_options_page(
            esc_html__( 'Ultimate Redirect Manager Page Settings', 'ultimate-redirect-manager' ), // Page title
            esc_html__( 'Ultimate Redirect Manager', 'ultimate-redirect-manager' ), // Menu title
            'manage_options', // Capability required to access the page
            'ultimate_404_page_settings', // Menu slug
            array( $this, 'ultimate_404_plugin_settings_page' ) // Callback function
        );

    }

    function delete_rule_callback() {
        if (isset($_POST['rule_id'])) {
            $rule_id = sanitize_text_field( $_POST['rule_id'] );

            // Code to delete the row from the database table
            // Modify this based on your database structure and requirements
            global $wpdb;
            $table_name = $wpdb->prefix . 'ultimate_redirect_rules';
            $wpdb->delete($table_name, array('id' => $rule_id));

            // Send a success response back to the JavaScript code
            wp_send_json_success('success');
        }
        // Always exit to avoid further execution
        wp_die();
    }

    public function ultimate_404_plugin_settings_page() {
        ?>
        <div class="ultimate-404-wrap">
            <h2 class="nav-tab-wrapper">
                <a href="#page-redirects" class="ultimate-404-nav-tab"><?php esc_html_e('Page Redirects', 'ultimate-redirect-manager'); ?></a>
                <a href="#captured-urls" class="ultimate-404-nav-tab"><?php esc_html_e('Captured 404 URLs', 'ultimate-redirect-manager'); ?></a>
                <a href="#error-logs" class="ultimate-404-nav-tab"><?php esc_html_e('404 Error Logs', 'ultimate-redirect-manager'); ?></a>
                
                
            </h2>

            <div id="page-redirects" class="ultimate-404-tab-content">
                <!-- Content for Page Redirects tab -->
                
                <?php require_once ULTIMATE_404_PLUGIN_DIR_LITE . '/ultimate-class/tabs-content/page-redirect.php'; ?>

            </div>

            <div id="captured-urls" class="ultimate-404-tab-content">
                <!-- Content for Captured 404 URLs tab -->
                <?php require_once ULTIMATE_404_PLUGIN_DIR_LITE . '/ultimate-class/tabs-content/captured-urls.php'; ?>        
            </div>

            <div id="error-logs" class="ultimate-404-tab-content">
                <?php require_once ULTIMATE_404_PLUGIN_DIR_LITE . '/ultimate-class/tabs-content/error-logs.php'; ?>
            </div>
            
        </div>
        

        <?php
    }
}
?>