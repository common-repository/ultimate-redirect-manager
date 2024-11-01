<?php
namespace Ultimate_Solution\Ultimate;

class Ultimate_404_Text_Domain {
    const TEXT_DOMAIN = 'ultimate-redirect-manager';

    public function __construct() {
        // Hook the load_plugin_textdomain function to the plugins_loaded action
        add_action('plugins_loaded', array($this, 'ultimate_404_load_plugin_textdomain'));
    }

    public function ultimate_404_load_plugin_textdomain() {
        // Load the plugin text domain for translation
        load_plugin_textdomain(Ultimate_404_Text_Domain::TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }
}
