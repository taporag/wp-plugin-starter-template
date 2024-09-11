<?php

/**
 * Plugin Name: Accessly
 * Description: A membership plugin with subscription management and payment gateway integration.
 * Version: 1.0.0
 * Author: Tanjim Alam
 * Text Domain: accessly
 */

namespace {NAMESPACE};

use {NAMESPACE}\Includes\Main;

defined('ABSPATH') || exit;

define('{NAMESPACE}_VERSION', '1.0.0');
define('{NAMESPACE}_FILE', __FILE__);
define('{NAMESPACE}_PATH',  plugin_dir_path(__FILE__));
define('{NAMESPACE}_URL',  plugin_dir_url(__FILE__));
define('{NAMESPACE}_ASSETS_SRC',  {NAMESPACE}_URL . 'assets/');
define('{NAMESPACE}_ASSETS',  {NAMESPACE}_URL . 'assets/');

require_once {NAMESPACE}_PATH . 'vendor/autoload.php';

new Main();