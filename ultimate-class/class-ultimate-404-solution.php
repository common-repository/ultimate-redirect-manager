<?php
namespace Ultimate_Solution\Ultimate;

class Ultimate_404_Solution {
    public function __construct() {
        // Register hooks
        add_action('template_redirect', array($this, 'custom_ultimate_404_page'));
    }

    public function custom_ultimate_404_page() {
        // Check if a custom 404 page is selected
        $custom_ultimate_404_page_id = get_option('custom_ultimate_404_page_id');
        if ($custom_ultimate_404_page_id && is_404()) {
            // Redirect to the selected page
            wp_redirect(get_permalink($custom_ultimate_404_page_id), 301);
            exit();
        } 

        if (!is_404()) {
            // Manual redirection for specific source URLs

            // Sanitize the requested URL
            $raw_url = sanitize_text_field( $_SERVER['REQUEST_URI'] );

            // Parse the sanitized URL to get the path
            $source_url = wp_parse_url( $raw_url, PHP_URL_PATH );

            global $wpdb;
            $table_name = $wpdb->prefix . 'ultimate_redirect_rules';

            // Prepare and execute the SQL query to fetch the destination URL based on the source URL
            $sql = $wpdb->prepare("SELECT destination_url FROM {$table_name} WHERE source_url = %s", $source_url);
            $destination_url = $wpdb->get_var($sql);


            // Initialize the redirect rules array
            $redirect_rules = array();

            // Check if the destination URL exists
            if ($destination_url) {
                $redirect_rules[$source_url] = $destination_url;
            }

            // Check if the source URL matches any manual redirect
            if (array_key_exists($source_url, $redirect_rules)) {
                // Redirect manually
                wp_redirect(home_url($redirect_rules[$source_url]), 301);
                exit();
            }
        }

    }
}

?>