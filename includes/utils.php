<?php 

/**
 * Logs data to the WordPress debug log if WP_DEBUG is enabled.
 *
 * @param mixed $data Data to log (string, number, array, object, etc.).
 *
 * - Logs only when WP_DEBUG is true.
 * - Converts arrays/objects to readable string format using print_r.
 * - Directly logs other data types.
 */
function write_log($data) {
	defined('WP_DEBUG') || define('WP_DEBUG', false);
	if (true === WP_DEBUG) {
		 if (is_array($data) || is_object($data)) {
			  error_log(print_r($data, true));
		 } else {
			  error_log($data);
		 }
	}
}


/**
 * Loads all files from the specified directory in a custom-defined order and optionally its subdirectories.
 *
 * This function includes files found in the specified directory and, 
 * if specified, its subdirectories. Files are loaded based on the provided order array,
 * with unlisted files loaded alphabetically.
 *
 * @param string $directory The directory path where files are located.
 * @param bool $include_sub_dirs Whether to include files from subdirectories.
 * @param array $order Optional. An array of filenames specifying the order in which to load files.
 * Example usage: load_files_from_directory('/path/to/your/functions', true, ['example.php', 'example3.php 'example2.php']);
 */
function load_files_from_directory($directory, $include_sub_dirs = true, $order = []) {
   // Ensure the provided directory path ends with a directory separator
   $directory = rtrim($directory, '/') . '/';
   
   // Acceptable extension
   $file_extension = 'php';

   // Check if the directory exists
   if (is_dir($directory)) {
       $files = [];
       $iterator = $include_sub_dirs 
           ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::LEAVES_ONLY)
           : new DirectoryIterator($directory);

       // Collect files into an array
       foreach ($iterator as $file) {
           if ($file->isFile() && $file->getExtension() === $file_extension) {
               $filename = $file->getFilename();
               $files[$filename] = $file->getRealPath();
           }
       }

       // Create an array to hold ordered files
       $ordered_files = [];

       // Load files based on custom order first
       foreach ($order as $filename) {
           if (isset($files[$filename])) {
               $ordered_files[$filename] = $files[$filename];
               unset($files[$filename]);
           }
       }

       // Load remaining files alphabetically
       ksort($files);

       // Merge custom ordered files with the remaining sorted files
       $final_files = array_merge($ordered_files, $files);

       // Include files in the defined order
       foreach ($final_files as $path) {
           require_once $path;
       }
   }
}

