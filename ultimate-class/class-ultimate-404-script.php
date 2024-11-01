<?php
namespace Ultimate_Solution\Ultimate;

class Ultimate_404_Script {
    // Define the plugin version as a constant
    const PLUGIN_VERSION = '1.0.0';

    public function __construct() {
        // Register hooks
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_admin_styles() {
        wp_enqueue_style( 'ultimate-404-admin-style', plugins_url( 'assets/css/ultimate-404-style.css', ULTIMATE_404_FILE_LITE ), array(), self::PLUGIN_VERSION );
        wp_enqueue_script( 'ultimate-404-script', plugins_url( 'assets/js/ultimate-404-script.js', ULTIMATE_404_FILE_LITE ), array( 'jquery' ), self::PLUGIN_VERSION, true );
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'ultimate-404-style', plugins_url( 'assets/css/ultimate-404-style.css', ULTIMATE_404_FILE_LITE ), array(), self::PLUGIN_VERSION );
        wp_enqueue_script( 'ultimate-404-script', plugins_url( 'assets/js/ultimate-404-script.js', ULTIMATE_404_FILE_LITE ), array( 'jquery' ), self::PLUGIN_VERSION, true );
    }
}
