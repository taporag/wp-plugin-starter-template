<?php

namespace {NAMESPACE}\Services;

use {NAMESPACE}\Includes\Loader;
use {NAMESPACE}\Services\Admin\Test;

defined('ABSPATH') || exit;

/**
 * Class Admin
 * Handles admin-specific functionality for the Accessly plugin.
 */
class Admin extends Loader {

    /**
     * Constructor to initialize hooks for admin functionality.
     */
    public function __construct() {
        parent::__construct();
    }

    protected function load_dependencies() {
        new Test(); // Services
    }

    /**
     * Initialize hooks for admin-related tasks.
     */
    public function init() {
        
    }


    /**
     * Setup database tables or default settings on plugin activation.
     */
    public function setup_database() {
        // Database setup code, such as creating tables or setting default options.
    }

    /**
     * Clean up settings or database entries on plugin deactivation.
     */
    public function cleanup_settings() {
        // Clean up settings or other tasks on deactivation.
    }
}
