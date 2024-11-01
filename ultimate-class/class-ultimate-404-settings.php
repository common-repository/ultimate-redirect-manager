<?php
namespace Ultimate_Solution\Ultimate;

class Ultimate_404_Settings {
    public function __construct() {
        // Register hooks
        add_action('admin_init', array($this, 'ultimate_404_register_settings'));
        add_action('admin_init', array($this, 'ultimate_404_redirect_rules_settings'));
    }

    public function ultimate_404_register_settings() {
        // Define a new settings section for page settings
        add_settings_section(
            'ultimate_404_page_settings_section', // Section ID
            '404 Page Redirection', // Section Title
            array($this, 'ultimate_section_callback'), // Callback for rendering section description
            'ultimate_404_page_settings' // Page where the section will be displayed
        );

        // Register a new settings field for selecting the custom 404 page
        add_settings_field(
            'custom_404_page_dropdown', // Field ID
            'Select Page', // Field Title
            array($this, 'ultimate_dropdown_callback'), // Callback for rendering the dropdown
            'ultimate_404_page_settings', // Page where the field will be displayed
            'ultimate_404_page_settings_section' // Section to which the field belongs
        );

        // Register the setting for the custom 404 page
        register_setting(
            'ultimate_custom_404_page_options', // Option group
            'custom_ultimate_404_page_id', // Option name
            array($this, 'ultimate_sanitize_page_id') // Sanitization callback
        );
    }

    public function ultimate_sanitize_page_id($input) {
        // Sanitize page ID here if needed
        return $input;
    }

    public function ultimate_section_callback() {
        // Section description (if any)
        echo '<p>' . esc_html__( 'Select a page to serve as your custom 404 error page.', 'ultimate-redirect-manager' ) . '</p>';
        
    }

    public function ultimate_dropdown_callback() {
        // Output the dropdown menu for selecting page
        $pages = get_pages();
        $selected_page = get_option('custom_ultimate_404_page_id');
        echo '<select id="custom_404_page_dropdown" name="custom_ultimate_404_page_id">';
        echo '<option value="">' . esc_html__( 'Select Page', 'ultimate-redirect-manager' ) . '</option>'; // Add a default option
        foreach ($pages as $page) {
            $selected = ($selected_page == $page->ID) ? 'selected' : '';
            echo '<option value="' . esc_attr($page->ID) . '" ' . esc_attr($selected) . '>' . esc_html( $page->post_title ) . '</option>';
        }
        echo '</select>';
    }


    public function ultimate_404_redirect_rules_settings() {
        // Define a new settings section for redirect rules
        add_settings_section(
            'ultimate_404_redirect_rules_section', // Section ID
            'Manual Redirect Table', // Section Title
            array($this, 'ultimate_redirect_rules_section_callback'), // Callback for rendering section description
            'ultimate_404_page_settings' // Page where the section will be displayed
        );

        // Add fields for managing redirect rules
        add_settings_field(
            'redirect_rules_field', // Field ID
            'Add a Manual Redirect', // Field Title
            array($this, 'ultimate_redirect_rules_field_callback'), // Callback for rendering the field
            'ultimate_404_page_settings', // Page where the field will be displayed
            'ultimate_404_redirect_rules_section' // Section to which the field belongs
        );
    }

    // Callback function for rendering the section description for redirect rules
    public function ultimate_redirect_rules_section_callback() {
        // Section description (if any)
        echo '<p>' . esc_html__( 'Manual Redirect Table is here.', 'ultimate-redirect-manager' ) . '</p>';
        // Display existing redirect rules from the database
        global $wpdb;
        $table_name = $wpdb->prefix . 'ultimate_redirect_rules';
        $redirect_rules = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        // Display the table for displaying existing redirect rules
        echo '<table class="wp-list-table widefat fixed">';
        // Add table headers for displaying existing redirect rules
        echo '<thead>';
        echo '<tr style="text-align: left;">';
        echo '<th style="width: 20%;">' . esc_html__('Source URL', 'ultimate-redirect-manager') . '</th>';
        echo '<th style="width:15%;">' . esc_html__('Status', 'ultimate-redirect-manager') . '</th>';
        echo '<th style="width:15%;">' . esc_html__('Type', 'ultimate-redirect-manager') . '</th>';
        echo '<th style="width: 20%;">' . esc_html__('Destination URL', 'ultimate-redirect-manager') . '</th>';
        echo '<th style="width: 10%;">' . esc_html__('Redirect Type', 'ultimate-redirect-manager') . '</th>';
        
        echo '<th style="width: 15%;">' . esc_html__('Created', 'ultimate-redirect-manager') . '</th>';
        
        echo '<th style="width: 5%;">' . esc_html__('Action', 'ultimate-redirect-manager') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ( $redirect_rules as $rule ) {
            echo '<tr>';
            echo '<td>' . esc_attr( $rule['source_url'] ) . '</td>';
            echo '<td>' . esc_attr( $rule['status'] ) . '</td>';
            echo '<td>' . esc_attr( $rule['type'] ) . '</td>';
            echo '<td>' . esc_attr( $rule['destination_url'] ) . '</td>';
            echo '<td>' . esc_attr( $rule['redirect_type'] ) . '</td>';
            
            echo '<td>' . esc_attr( $rule['created'] ) . '</td>';
            
            echo '<td><button class="delete-row" data-rule-id="' . esc_attr( $rule['id'] ) . '">Delete</button></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';

        
    }

    // Callback function for rendering the field to manage redirect rules
    public function ultimate_redirect_rules_field_callback() {

        if (isset($_POST['submit'])) {
            // Form submitted, insert data into the database
            $source_url = sanitize_text_field($_POST['source_url']);
            $destination_url = sanitize_text_field($_POST['destination_url']);
            $redirect_type = sanitize_text_field($_POST['redirect_type']);

            global $wpdb;
            $table_name = $wpdb->prefix . 'ultimate_redirect_rules';
            $wpdb->insert(
                $table_name,
                array(
                    'source_url' => $source_url,
                    'destination_url' => $destination_url,
                    'redirect_type' => $redirect_type,
                    // Set default values for other columns
                    'status' => 'Active',
                    'type' => 'Manual', // Assuming this is a custom rule
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%s',
                    
                )
            );
        }

        // Output the form and table for managing redirect rules
        $this->output_redirect_rules_form();
        
    }

    public function output_redirect_rules_form() {

        echo '<form method="post" action="">';
        echo '<table class="form-table">';
        // Add fields for managing redirect rules (source URL, destination URL, redirect type)
        echo '<tr>';
        echo '<th scope="row">' . esc_html__('Source URL', 'ultimate-redirect-manager') . '</th>';
        echo '<td><input type="text" name="source_url" placeholder="/page-url/" value="" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th scope="row">' . esc_html__('Destination URL', 'ultimate-redirect-manager') . '</th>';
        echo '<td><input type="text" name="destination_url" placeholder="/page-url/" value="" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th scope="row">' . esc_html__('Redirect Type', 'ultimate-redirect-manager') . '</th>';
        echo '<td>';
        echo '<select name="redirect_type">';
        echo '<option value="301">' . esc_html__('Default', 'ultimate-redirect-manager') . '</option>';
        echo '<option value="301">' . esc_html__('301 Moved Permanently', 'ultimate-redirect-manager') . '</option>';
        echo '<option value="302">' . esc_html__('302 Temporary Redirect', 'ultimate-redirect-manager') . '</option>';
        
        echo '</select>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
        echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="' . esc_html__('Save Rule', 'ultimate-redirect-manager') . '"></p>';
        echo '</form>';
    }


}
?>
