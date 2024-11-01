<?php
/**
 * Plugin Name: Ultimate Redirect Manager 
 * Description: Ultimate Redirect Manager is the most advanced redirection tool for WordPress. This Redirect tool will redirect your website's error links and URLs to the pages you select.
 * Version: 1.0.0
 * Plugin URI: https://wordpress.org/plugins/ultimate-redirect-manager/
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: ultimate-redirect-manager
 * Domain Path: /languages
 * Requires PHP: 7.0.0
 * Requires at least: 5.5
 */

namespace Ultimate_Solution;

defined('ABSPATH') or die('Hey, what are you doing here? You silly human!');

// Define Custom Constant Variables
define('ULTIMATE_404_VERSION_LITE', '1.0.0');
define('ULTIMATE_404_PLUGIN_DIR_LITE', plugin_dir_path(__FILE__));
define('ULTIMATE_404_PLUGIN_URL_LITE', plugin_dir_url(__FILE__));
define('ULTIMATE_404_FILE_LITE', __FILE__);
define('ULTIMATE_404_BASENAME_LITE', plugin_basename(__FILE__));

use Ultimate_Solution\Ultimate\Ultimate_404_Menu;
use Ultimate_Solution\Ultimate\Ultimate_404_Settings;
use Ultimate_Solution\Ultimate\Ultimate_404_Solution;
use Ultimate_Solution\Ultimate\Ultimate_404_Script;
use Ultimate_Solution\Ultimate\Ultimate_404_Text_Domain;
use Ultimate_Solution\Ultimate\Ultimate_404_Activation;

require_once ULTIMATE_404_PLUGIN_DIR_LITE . '/ultimate-class/class-ultimate-404-menu.php';
require_once ULTIMATE_404_PLUGIN_DIR_LITE . '/ultimate-class/class-ultimate-404-settings.php';
require_once ULTIMATE_404_PLUGIN_DIR_LITE . '/ultimate-class/class-ultimate-404-solution.php';
require_once ULTIMATE_404_PLUGIN_DIR_LITE . '/ultimate-class/class-ultimate-404-script.php';
require_once ULTIMATE_404_PLUGIN_DIR_LITE . '/ultimate-class/class-ultimate-404-textdomain.php';
require_once ULTIMATE_404_PLUGIN_DIR_LITE . '/ultimate-class/class-ultimate-404-activation.php';

$ultimate_404_menu = new Ultimate_404_Menu();
$ultimate_404_settings = new Ultimate_404_Settings();
$ultimate_404_solution = new Ultimate_404_Solution();
$ultimate_404_script = new Ultimate_404_Script();
$ultimate_404_textdomain = new Ultimate_404_Text_Domain();
$ultimate_404_activation = new Ultimate_404_Activation();

register_activation_hook(__FILE__, array('Ultimate_Solution\Ultimate\Ultimate_404_Activation', 'ultimate_404_database_table'));
register_activation_hook(__FILE__, array('Ultimate_Solution\Ultimate\Ultimate_404_Activation', 'ultimate_404_captured_url_table'));
register_activation_hook(__FILE__, array('Ultimate_Solution\Ultimate\Ultimate_404_Activation', 'ultimate_404_redirect_rules_table'));