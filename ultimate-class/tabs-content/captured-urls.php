<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- Content for Captured 404 URLs tab -->
<div class="captured-urls">
    <h2><?php esc_html_e('Captured 404 URLs', 'ultimate-redirect-manager'); ?></h2>
    <table class="wp-list-table widefat fixed">
        <thead>
            <tr style="text-align: left;">
                <th style="width: 50%;"><?php esc_html_e('URL', 'ultimate-redirect-manager'); ?></th>
                <th style="width: 10%;"><?php esc_html_e('Hits', 'ultimate-redirect-manager'); ?></th>
                <th style="width: 20%;"><?php esc_html_e('Created', 'ultimate-redirect-manager'); ?></th>
                <th style="width: 20%;"><?php esc_html_e('Last Used', 'ultimate-redirect-manager'); ?></th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'ultimate_captured_404_url'; 

            $logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY hits DESC");

            foreach ($logs as $log) {
                echo '<tr>';
                echo '<td>' . esc_html($log->url) . '</td>';
                echo '<td>' . esc_html($log->hits) . '</td>';
                echo '<td>' . esc_html($log->created) . '</td>';
                echo '<td>' . esc_html($log->last_used) . '</td>';
                
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>