<?php

/**
 * Configuration Structure for {NAMESPACE} Plugin Dependencies
 *
 * This file returns an array with the following structure:
 *
 * 'plugins' => [
 *     'plugin-file/plugin-file.php' => [
 *         'message' => 'Required message for the plugin',
 *         'slug' => 'slug-of-the-plugin',
 *         'installable' => true/false, // Whether the plugin can be installed (Only tested with plugins that belong to https://wordpress.org/plugins/)
 *         'type' => 'error'|'warning'|'info'|'success', // Type of notice to display
 *     ],
 *     // More plugins...
 * ]
 *
 * Example:
 * return [
 *     'plugins' => [
 *         'plugin-name/plugin-name.php' => [
 *             'message' => 'Plugin name is required',
 *             'slug' => 'plugin-name',
 *             'installable' => true,
 *             'type' => 'error',
 *         ],
 *         'another-plugin/another-plugin.php' => [
 *             'message' => 'Another Plugin is required. Install guide <a href="https://example.com/abc-plugin-install-guide">here</a>',
 *             'slug' => 'another-plugin',
 *             'installable' => false,
 *             'type' => 'warning',
 *         ],
 *     ]
 * ];
 */

return [
    'plugins' => [
    'podcast-player/podcast-player.php' => [
        'message' => 'Podcast Player is required',
        'slug' => 'podcast-player',
        'installable' => true,
        'type' => 'error', // Use 'error', 'warning', 'info', or 'success' for notice types.
    ],
    ],
];
