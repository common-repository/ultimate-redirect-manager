<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="page-redirects wrap">

    <form method="post" action="options.php">
        <?php
        // Output nonce, action, and option_page fields
        settings_fields('ultimate_custom_404_page_options');
        // Output the settings section
        do_settings_sections('ultimate_404_page_settings');
        // Output Save Settings button
        submit_button('Save Settings');
        ?>
    </form>
</div>