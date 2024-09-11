<?php

namespace {NAMESPACE}\Utils;

/**
 * Class Hook
 *
 * A utility class for managing custom WordPress actions and filters in the `Accessly` plugin.
 * 
 * Purpose:
 * - Prefixing: Automatically prefixes all custom hook names with the constant `{NAMESPACE}_PREFIX` to ensure uniqueness and avoid conflicts with other plugins or themes.
 * - Consistency: Provides a standardized method for registering and managing custom hooks, promoting consistent coding practices.
 * - Encapsulation: Centralizes the management of custom hooks, making the codebase more organized, maintainable, and easier to extend.
 *
 * @package {NAMESPACE}\Utils
 */
class Hook {

   /**
    * Add a prefixed action hook.
    *
    * @param string   $hook          The name of the hook.
    * @param callable $callback      The callback to be executed.
    * @param int      $priority      The priority of the hook.
    * @param int      $accepted_args The number of arguments accepted.
    */
   public static function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
      add_action(self::prefix($hook), $callback, $priority, $accepted_args);
   }

   /**
    * Execute a prefixed action.
    *
    * @param string $hook The name of the hook.
    * @param mixed  ...$args Optional. Additional arguments passed to the callback functions.
    */
   public static function do_action($hook, ...$args) {
      do_action(self::prefix($hook), ...$args);
   }

   /**
    * Add a prefixed filter hook.
    *
    * @param string   $hook          The name of the hook.
    * @param callable $callback      The callback to be executed.
    * @param int      $priority      The priority of the hook.
    * @param int      $accepted_args The number of arguments accepted.
    */
   public static function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
      add_filter(self::prefix($hook), $callback, $priority, $accepted_args);
   }

   /**
    * Apply a prefixed filter hook.
    *
    * @param string $hook  The name of the hook.
    * @param mixed  $value The value on which the filters hooked to `$hook` are applied.
    * @param mixed  ...$args Additional variables passed to the callback functions.
    * @return mixed The filtered value after all hooked functions are applied.
    */
   public static function apply_filters($hook, $value, ...$args) {
      return apply_filters(self::prefix($hook), $value, ...$args);
   }

   /**
    * Remove a prefixed action hook.
    *
    * @param string   $hook     The name of the hook.
    * @param callable $callback The callback to be removed.
    * @param int      $priority The priority of the hook.
    */
   public static function remove_action($hook, $callback, $priority = 10) {
      remove_action(self::prefix($hook), $callback, $priority);
   }

   /**
    * Remove a prefixed filter hook.
    *
    * @param string   $hook     The name of the hook.
    * @param callable $callback The callback to be removed.
    * @param int      $priority The priority of the hook.
    */
   public static function remove_filter($hook, $callback, $priority = 10) {
      remove_filter(self::prefix($hook), $callback, $priority);
   }

   /**
    * Check if a prefixed filter is attached to a hook.
    *
    * @param string   $hook     The name of the hook.
    * @param callable $callback Optional. The callback to check for.
    * @param int      $priority Optional. The priority of the hook to check for. Default is 10.
    * @return bool|int If callback is omitted, returns boolean for whether the hook has anything registered.
    *                  When checking a specific function, the priority of that hook is returned, or false if the function is not attached.
    */
   public static function has_filter($hook, $callback = false, $priority = 10) {
      return has_filter(self::prefix($hook), $callback);
   }

   /**
    * Check if a prefixed action is attached to a hook.
    *
    * @param string   $hook     The name of the hook.
    * @param callable $callback Optional. The callback to check for.
    * @param int      $priority Optional. The priority of the hook to check for. Default is 10.
    * @return bool|int If callback is omitted, returns boolean for whether the hook has anything registered.
    *                  When checking a specific function, the priority of that hook is returned, or false if the function is not attached.
    */
   public static function has_action($hook, $callback = false, $priority = 10) {
      return has_action(self::prefix($hook), $callback);
   }

   /**
    * Prefix a hook name with the constant {NAMESPACE}_PREFIX.
    *
    * @param string $hook The original hook name.
    * @return string The prefixed hook name.
    */
   protected static function prefix($hook) {
      return {NAMESPACE}_PREFIX . $hook;
   }
}
