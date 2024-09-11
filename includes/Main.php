<?php

namespace {NAMESPACE}\Includes;
use {NAMESPACE}\Services\Admin;
use {NAMESPACE}\Services\Web;


defined('ABSPATH') || exit;

/**
 * The main class responsible for initializing the plugin.
 */
class Main {

   /**
    * Instance of the Admin class.
    *
    * @var object
    */
   private $admin;

   /**
    * Instance of the Web class.
    *
    * @var object
    */
   private $web;

   /**
    * Loader constructor.
    * Initializes hooks and loads necessary classes.
    */
   public function __construct() {
      $this->load_config();
      $this->load_dependencies();
      $this->init_hooks();
   }

   /**
    * Loads configs.
    */
   private function load_config() {
      // Directory where the function files are located
      $configs_dir = {NAMESPACE}_PATH . 'config/';
      load_files_from_directory($configs_dir);

   }

   /**
    * Loads all required files and dependencies.
    */
   private function load_dependencies() {
      // Load necessary classes
      new DependencyChecker();
   }

   /**
    * Registers hooks for activation, deactivation, and initialization.
    */
   private function init_hooks() {
      // Activation and deactivation hooks
      register_activation_hook({NAMESPACE}_FILE, [$this, 'activate']);
      register_deactivation_hook({NAMESPACE}_FILE, [$this, 'deactivate']);

      // Initialize other hooks
      add_action('init', [$this, 'init']);
   }

   /**
    * Code to execute on plugin activation.
    */
   public function activate() {
      // Example: Set up default options or create database tables
      if (method_exists($this->admin, 'setup_database')) {
         $this->admin->setup_database();
      }
   }

   /**
    * Code to execute on plugin deactivation.
    */
   public function deactivate() {
      // Example: Clean up options or other settings if necessary
      if (method_exists($this->admin, 'cleanup_settings')) {
         $this->admin->cleanup_settings();
      }
   }

   /**
    * Code to execute on plugin deactivation.
    */
   public function init() {
      // Load admin
      $this->admin = new Admin();
      // Load frontend
      $this->web = new Web();
   }

   
}
