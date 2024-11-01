<?php

namespace Ultimate_Solution\Ultimate;

class Ultimate_404_Activation {

    public static function ultimate_404_database_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ultimate_404_logs';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            url varchar(255) NOT NULL,
            ip_address varchar(50) NOT NULL,
            reference_link varchar(255) NOT NULL,
            action varchar(255) NOT NULL,
            user_role varchar(50) NOT NULL,
            timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function ultimate_404_captured_url_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ultimate_captured_404_url';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            url varchar(255) NOT NULL,
            hits mediumint(9) DEFAULT 0,
            created datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            last_used datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function ultimate_404_redirect_rules_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ultimate_redirect_rules';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            source_url VARCHAR(255) NOT NULL,
            status VARCHAR(255) NOT NULL,
            type VARCHAR(255) NOT NULL,
            destination_url VARCHAR(255) NOT NULL,
            redirect_type VARCHAR(9) NOT NULL,
            
            created datetime DEFAULT CURRENT_TIMESTAMP NOT NULL
            
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

}
