<?php
/**
 * Initializes the widgets for the JEC Portfolio plugin.
 *
 * This file includes the necessary widget files for the JEC Portfolio plugin.
 *
 * @package JEC_Portfolio
 * @version 1.0
 */

 if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include the Position Widget
require_once plugin_dir_path(__FILE__) . 'position/index.php';

// Include the Profile Widget
require_once plugin_dir_path(__FILE__) . 'profile/index.php';