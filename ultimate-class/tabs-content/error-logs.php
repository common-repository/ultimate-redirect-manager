<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="error-logs">
    <h2><?php esc_html_e('Error Logs', 'ultimate-redirect-manager'); ?></h2>
    <table class="wp-list-table widefat fixed">
        <thead>
            <tr style="text-align: left;">
                <th style="width: 35%;"><?php esc_html_e('URL', 'ultimate-redirect-manager'); ?></th>
                <th style="width: 10%;"><?php esc_html_e('IP Address', 'ultimate-redirect-manager'); ?></th>
                <th style="width: 15%;"><?php esc_html_e('Reference', 'ultimate-redirect-manager'); ?></th>
                <th style="width: 10%;"><?php esc_html_e('Action', 'ultimate-redirect-manager'); ?></th>
                <th style="width: 15%;"><?php esc_html_e('User', 'ultimate-redirect-manager'); ?></th>
                <th style="width: 15%;"><?php esc_html_e('Date', 'ultimate-redirect-manager'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'ultimate_404_logs'; // Replace with your table name

            // Check if the logs are already cached
            $cache_key = 'ultimate_404_logs';
            $logs = wp_cache_get($cache_key);

            if ($logs === false) {
                // Cache is empty or expired, fetch logs from the database
                $logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY timestamp DESC");

                // Cache the results for future use
                wp_cache_set($cache_key, $logs, '', 3600); // Cache for 1 hour
            }

            foreach ($logs as $log) {
                echo '<tr>';
                echo '<td>' . esc_html($log->url) . '</td>';
                echo '<td>' . esc_html($log->ip_address) . '</td>';
                echo '<td>' . esc_html($log->reference_link) . '</td>';
                echo '<td>' . esc_html($log->action) . '</td>';
                echo '<td>' . esc_html($log->user_role) . '</td>';
                echo '<td>' . esc_html($log->timestamp) . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>
