<?php 

namespace {NAMESPACE}\Includes;

use {NAMESPACE}\Includes\Notice;

/**
 * Class DependencyChecker
 *
 * Checks for required plugins, displays notices if they are missing, and handles installation and activation.
 */
class DependencyChecker {

    private $config;

    public function __construct() {
        $this->config = require {NAMESPACE}_PATH . 'config/dependencies.php' ;

        // Validate the structure of the config
        if (!$this->validate_config($this->config)) {
            wp_die('Invalid configuration format. Please check the documentation for the correct structure.');
        }
        // If there is no plugins then no farther action required
        if  (!isset($this->config['plugins']) || count($this->config['plugins']) < 1) {
            return;
        }
        add_action('admin_notices', [$this, 'check_dependencies']);
        add_action('admin_footer', [$this, 'enqueue_plugin_installer_script']);
        add_action('wp_ajax_aly_bulk_install_plugins', [$this, 'bulk_install_and_activate_plugins']);
    }

   /**
     * Validate the configuration structure.
     *
     * @param array $config
     * @return bool
     */
    private function validate_config(array $config): bool {
        // Check if 'plugins' is set and is an array
        if (isset($config['plugins']) && is_array($config['plugins'])) {
            // Validate each plugin entry if 'plugins' is not empty
            foreach ($config['plugins'] as $plugin_file => $plugin) {
                if (!is_array($plugin)
                    || !isset($plugin['message'], $plugin['slug'], $plugin['installable'], $plugin['type'])
                    || !is_string($plugin['message'])
                    || !is_string($plugin['slug'])
                    || !is_bool($plugin['installable'])
                    || !in_array($plugin['type'], ['error', 'warning', 'info', 'success'], true)) {
                    return false;
                }
            }
        }
        return true;
    }


    /**
     * Check for required plugins and display notices if missing.
     */
    public function check_dependencies() {
        $required_plugins = $this->config['plugins'];

        $bulk_actions = '';
        $notices = [];

        // Check if there are plugins to install
        if ($this->any_plugins_to_install($required_plugins)) {
            // Bulk action buttons
            $bulk_actions = '
                <button id="aly-select-all" class="button">Select All</button>
                <button id="aly-install-selected" class="button button-primary">Install Selected</button>
                <div id="aly-progress-container" style="margin-top: 10px;"></div>
            ';

            // Prepare notices for missing plugins
            foreach ($required_plugins as $plugin_file => $plugin) {
                if (!is_plugin_active($plugin_file) && !file_exists(WP_PLUGIN_DIR . '/' . $plugin_file)) {
                    if (isset($plugin['installable']) && $plugin['installable'] === true ) {
                        $checkbox = '<input type="checkbox" class="aly-plugin-checkbox" data-slug="' . esc_attr($plugin['slug']) . '" /> ';
                    } else {
                        $checkbox = '';
                    }

                    $notices[] = $checkbox . $plugin['message'];
                }
            }

            // Display notices and bulk actions if there are missing plugins
            if (!empty($notices)) {
                $bulk_actions = $this->any_plugins_to_installable($required_plugins) ? $bulk_actions : '';
                $notice_content = $bulk_actions . '<div class="aly-plugin-notices">' . implode('<br>', $notices) . '</div>';
                Notice::display('error', $notice_content, false);
            }
        }
    }

    /**
     * Check if there are any plugins to install
     */
    private function any_plugins_to_install($plugins) {

        foreach ($plugins as $plugin_file => $plugin) {
            if (!is_plugin_active($plugin_file) && !file_exists(WP_PLUGIN_DIR . '/' . $plugin_file)) {
                return true;
            }
        }
        return false;
    }

        /**
     * Check if there are any installable plugins to install
     */
    private function any_plugins_to_installable($plugins) {
        foreach ($plugins as $plugin_file => $plugin) {
            // Check if the plugin is installable and not yet active or installed
            if (
                isset($plugin['installable']) && $plugin['installable'] === true
                && !is_plugin_active($plugin_file)
                && !file_exists(WP_PLUGIN_DIR . '/' . $plugin_file)
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Enqueue the plugin installer script and localize it with AJAX URL and nonce.
     */
    public function enqueue_plugin_installer_script() {
        wp_enqueue_script('aly-plugin-installer', {NAMESPACE}_ASSETS . 'admin/inc/plugin-installer.js', [], null, true);

        // Localize script with AJAX URL and nonce
        wp_localize_script('aly-plugin-installer', 'alyPluginInstaller', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('aly_bulk_install_plugins'),
        ]);
    }

    /**
     * Handle the AJAX request for bulk installing and activating plugins.
     */
    public function bulk_install_and_activate_plugins() {
        check_ajax_referer('aly_bulk_install_plugins', 'nonce');

        if (!current_user_can('install_plugins')) {
            wp_send_json_error(['message' => 'You do not have permission to install plugins.']);
        }

        if (empty($_POST['slugs'])) {
            wp_send_json_error(['message' => 'No plugin slugs provided.']);
        }
    
        $slugs = json_decode(stripslashes($_POST['slugs']), true); // Decode JSON string to array
    
        if (!is_array($slugs)) {
            wp_send_json_error(['message' => 'Invalid data format for plugin slugs.']);
        }
        
        $results = [];

        include_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // Required for plugins_api() function.
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php'; // Required for Plugin_Upgrader.

        foreach ($slugs as $slug) {
            $api = plugins_api('plugin_information', ['slug' => $slug, 'fields' => ['sections' => false]]);

            if (is_wp_error($api)) {
                $results[] = ['slug' => $slug, 'success' => false, 'message' => 'Failed to retrieve plugin information.'];
                continue;
            }

            $upgrader = new \Plugin_Upgrader(new \Automatic_Upgrader_Skin());
            $installed = $upgrader->install($api->download_link);

            if (is_wp_error($installed)) {
                $results[] = ['slug' => $slug, 'success' => false, 'message' => $installed->get_error_message()];
                continue;
            }

            $plugin_file = $api->slug . '/' . $api->slug . '.php';
            $activation_result = activate_plugin($plugin_file);

            if (is_wp_error($activation_result)) {
                $results[] = ['slug' => $slug, 'success' => false, 'message' => $activation_result->get_error_message()];
            } else {
                $results[] = ['slug' => $slug, 'success' => true, 'plugin_file' => $plugin_file];
            }
        }

        wp_send_json($results);
    }
}
