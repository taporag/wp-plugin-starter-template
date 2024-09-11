<?php

namespace {NAMESPACE}\Utils;


class Meta {

   /**
    * Adds a meta box to the specified screen.
    *
    * @param string $id       The ID of the meta box.
    * @param string $title    The title of the meta box.
    * @param string $screen   The screen or post type on which the box will appear (default: 'post').
    * @param string $context  The context within the screen where the box should display (default: 'advanced').
    * @param string $priority The priority within the context where the box should display (default: 'default').
    * @param array  $fields   An array of field configurations for the meta box.
    * 
    * The $fields array structure:
    * [
    *     [
    *         'id'          => 'unique_field_id',      // (string) Unique ID for the field (used as meta key).
    *         'label'       => 'Field Label',          // (string) Label for the field.
    *         'type'        => 'text',                 // (string) Field type (e.g., 'text', 'textarea', 'checkbox', etc.).
    *         'placeholder' => 'Enter text here',      // (string) (Optional) Placeholder text for input fields.
    *         'description' => 'Description of field', // (string) (Optional) Description or helper text.
    *         'options'     => [                       // (array) (Optional) Array of options for select, radio, or checkbox fields.
    *             'value1' => 'Option 1',
    *             'value2' => 'Option 2'
    *         ],
    *         'default'     => 'default_value',        // (mixed) (Optional) Default value for the field.
    *         'attributes'  => [                       // (array) (Optional) Additional HTML attributes (e.g., 'readonly' => true).
    *             'class' => 'custom-class',
    *             'style' => 'width: 100%;'
    *         ]
    *     ],
    * ]
    */
   public static function add_meta_box($id, $title, $screen = 'post', $context = 'advanced', $priority = 'default', $fields = []) {
      add_action('add_meta_boxes', function() use ($id, $title, $screen, $context, $priority, $fields) {
         add_meta_box(
               $id,
               $title,
               function($post) use ($id, $fields) {
                  wp_nonce_field($id . '_nonce_action', $id . '_nonce_field');
                  foreach ($fields as $field) {
                     $value = get_post_meta($post->ID, $field['id'], true);
                     self::render_field($field, $value);
                  }
               },
               $screen,
               $context,
               $priority
         );
      });
   }

    // Static method to render a field
   protected static function render_field($field, $value) {
      echo '<p>';
      if (!empty($field['label'])) {
         echo '<label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . '</label><br>';
      }

      switch ($field['type']) {
         case 'text':
               echo '<input type="text" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" value="' . esc_attr($value) . '" class="widefat" />';
               break;
         case 'textarea':
               echo '<textarea id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" rows="5" class="widefat">' . esc_textarea($value) . '</textarea>';
               break;
         case 'checkbox':
               echo '<input type="checkbox" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" ' . checked($value, 'on', false) . ' />';
               break;
         case 'select':
               echo '<select id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" class="widefat">';
               foreach ($field['options'] as $key => $label) {
                  echo '<option value="' . esc_attr($key) . '" ' . selected($value, $key, false) . '>' . esc_html($label) . '</option>';
               }
               echo '</select>';
               break;
         case 'radio':
               echo '<fieldset>';
               foreach ($field['options'] as $key => $label) {
                  echo '<label><input type="radio" name="' . esc_attr($field['id']) . '" value="' . esc_attr($key) . '" ' . checked($value, $key, false) . ' /> ' . esc_html($label) . '</label><br />';
               }
               echo '</fieldset>';
               break;
         default:
               break;
      }

      if (!empty($field['description'])) {
         echo '<p class="description">' . esc_html($field['description']) . '</p>';
      }
      echo '</p>';
   }

   // Static method to save meta box data
   public static function save_meta_box_data($id, $fields, $post_id) {
      // Verify nonce
      if (!isset($_POST[$id . '_nonce_field']) || !wp_verify_nonce($_POST[$id . '_nonce_field'], $id . '_nonce_action')) {
         return;
      }

      // Prevent auto-saving
      if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
         return;
      }

      // Check user permissions
      if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
         if (!current_user_can('edit_page', $post_id)) {
               return;
         }
      } else {
         if (!current_user_can('edit_post', $post_id)) {
               return;
         }
      }

      // Save each field
      foreach ($fields as $field) {
         if (isset($_POST[$field['id']])) {
               $value = sanitize_text_field($_POST[$field['id']]);

               if ($field['type'] === 'textarea') {
                  $value = sanitize_textarea_field($_POST[$field['id']]);
               } elseif ($field['type'] === 'checkbox') {
                  $value = 'on';  // Checkboxes are either 'on' or not set
               }

               update_post_meta($post_id, $field['id'], $value);
         } else {
               // Delete meta if checkbox unchecked or field is empty
               delete_post_meta($post_id, $field['id']);
         }
      }
   }

   // Generic method to get meta value based on type
   public static function get_meta($object_id, $key, $type = 'post') {
      switch ($type) {
         case 'user':
               return get_user_meta($object_id, $key, true);
         case 'term':
               return get_term_meta($object_id, $key, true);
         case 'comment':
               return get_comment_meta($object_id, $key, true);
         default:
               return get_post_meta($object_id, $key, true);
      }
   }

   // Generic method to update meta value based on type
   public static function update_meta($object_id, $key, $value, $type = 'post') {
      switch ($type) {
         case 'user':
               return update_user_meta($object_id, $key, $value);
         case 'term':
               return update_term_meta($object_id, $key, $value);
         case 'comment':
               return update_comment_meta($object_id, $key, $value);
         default:
               return update_post_meta($object_id, $key, $value);
      }
   }

   // Generic method to delete meta value based on type
   public static function delete_meta($object_id, $key, $type = 'post') {
      switch ($type) {
         case 'user':
               return delete_user_meta($object_id, $key);
         case 'term':
               return delete_term_meta($object_id, $key);
         case 'comment':
               return delete_comment_meta($object_id, $key);
         default:
               return delete_post_meta($object_id, $key);
      }
   }

   /**
    * Adds a user meta box to the user profile screen.
    *
    * @param string $id     The ID of the meta box.
    * @param string $title  The title of the meta box.
    * @param array  $fields An array of field configurations for the user meta box.
    * 
    * The $fields array structure:
    * [
    *     [
    *         'id'          => 'unique_field_id',      // (string) Unique ID for the field (used as meta key).
    *         'label'       => 'Field Label',          // (string) Label for the field.
    *         'type'        => 'text',                 // (string) Field type (e.g., 'text', 'textarea', 'checkbox', etc.).
    *         'placeholder' => 'Enter text here',      // (string) (Optional) Placeholder text for input fields.
    *         'description' => 'Description of field', // (string) (Optional) Description or helper text.
    *         'options'     => [                       // (array) (Optional) Array of options for select, radio, or checkbox fields.
    *             'value1' => 'Option 1',
    *             'value2' => 'Option 2'
    *         ],
    *         'default'     => 'default_value',        // (mixed) (Optional) Default value for the field.
    *         'attributes'  => [                       // (array) (Optional) Additional HTML attributes (e.g., 'readonly' => true).
    *             'class' => 'custom-class',
    *             'style' => 'width: 100%;'
    *         ]
    *     ]
    * ]
    */

   public static function add_user_meta_box($id, $title, $fields = []) {
      add_action('show_user_profile', function($user) use ($id, $title, $fields) {
         echo '<h3>' . esc_html($title) . '</h3>';
         echo '<table class="form-table">';
         foreach ($fields as $field) {
               $value = get_user_meta($user->ID, $field['id'], true);
               self::render_field($field, $value);
         }
         echo '</table>';
         wp_nonce_field($id . '_nonce_action', $id . '_nonce_field');
      });

      add_action('edit_user_profile', function($user) use ($id, $title, $fields) {
         echo '<h3>' . esc_html($title) . '</h3>';
         echo '<table class="form-table">';
         foreach ($fields as $field) {
               $value = get_user_meta($user->ID, $field['id'], true);
               self::render_field($field, $value);
         }
         echo '</table>';
         wp_nonce_field($id . '_nonce_action', $id . '_nonce_field');
      });

      // Save user meta data
      add_action('personal_options_update', function($user_id) use ($id, $fields) {
         self::save_user_meta_box_data($id, $fields, $user_id);
      });

      add_action('edit_user_profile_update', function($user_id) use ($id, $fields) {
         self::save_user_meta_box_data($id, $fields, $user_id);
      });
   }

   // Static method to save user meta box data
   public static function save_user_meta_box_data($id, $fields, $user_id) {
      if (!isset($_POST[$id . '_nonce_field']) || !wp_verify_nonce($_POST[$id . '_nonce_field'], $id . '_nonce_action')) {
         return;
      }

      foreach ($fields as $field) {
         if (isset($_POST[$field['id']])) {
               $value = sanitize_text_field($_POST[$field['id']]);

               if ($field['type'] === 'textarea') {
                  $value = sanitize_textarea_field($_POST[$field['id']]);
               } elseif ($field['type'] === 'checkbox') {
                  $value = 'on';
               }

               update_user_meta($user_id, $field['id'], $value);
         } else {
               delete_user_meta($user_id, $field['id']);
         }
      }
   }
}