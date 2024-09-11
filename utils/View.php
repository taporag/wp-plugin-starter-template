<?php

namespace {NAMESPACE}\Utils;


class View {

   /**
    * Load a template file.
    *
    * @param string $slug  The slug name for the generic template.
    * @param string $name  The name of the specialized template (optional).
    * @param array  $args  An associative array of arguments to pass to the template (optional).
    * @param string $type  The type of template to load, either 'general' or 'admin' (default: 'general').
    */
   public static function get_template_part($slug, $name = '', $args = [], $type = 'general') {
      // Construct the file name based on the slug and name
      $template = $slug;
      if ($name) {
         $template = "{$slug}-{$name}";
      }

      // Determine the directory based on the template type
      $subdir = $type === 'admin' ? 'admin/' : '';
      
      // Look for the template file in the plugin's views directory
      $file = {NAMESPACE}_PATH . "views/{$subdir}{$template}.php";

      // If the file exists, load it
      if (file_exists($file)) {
         // Make the arguments available in the template
         if (!empty($args) && is_array($args)) {
               extract($args);
         }

         include $file;
      } else {
         echo '<p>Template not found.</p>';
      }
   }
}