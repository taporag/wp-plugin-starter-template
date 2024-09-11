<?php

namespace {NAMESPACE}\Utils;

/**
 * Class Notice
 *
 * A utility class for managing and displaying WordPress admin notices.
 */
class Notice {

    // Define supported notice types and their associated classes
    private static $notice_types = [
        'danger'  => 'error',         // Red background for errors
        'info'    => 'updated',       // Green background for success or information
        'warning' => 'notice-warning' // Yellow background for warnings
    ];

    /**
     * Display an admin notice.
     *
     * @param string $type The type of notice (danger, info, warning).
     * @param string $message The message to display.
     */
    public static function display($type, $message, $esc_html = true) {
        // Ensure notice type is valid or fallback to default type
        $notice_class = self::get_notice_class($type);

        echo '<div class="' . esc_attr($notice_class) . '"><p>';
        echo $esc_html === true ? esc_html($message) : $message;
        echo '</p></div>';
    }

    /**
     * Get the CSS class for the notice type.
     *
     * @param string $type The type of notice.
     * @return string The CSS class for the notice type.
     */
    private static function get_notice_class($type) {
        // Validate the type and return corresponding CSS class or default to 'error'
        return isset(self::$notice_types[$type]) ? self::$notice_types[$type] : 'error';
    }

    /**
     * Display a success notice.
     *
     * @param string $message The message to display.
     */
    public static function success($message) {
        self::display('info', $message);
    }

    /**
     * Display a warning notice.
     *
     * @param string $message The message to display.
     */
    public static function warning($message) {
        self::display('warning', $message);
    }

    /**
     * Display an error notice.
     *
     * @param string $message The message to display.
     */
    public static function error($message) {
        self::display('danger', $message);
    }
}
