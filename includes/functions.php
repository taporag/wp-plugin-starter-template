<?php
/*
 * This file contains functions, constants, and other code 
 * that are globally accessible throughout the entire plugin.
 */

defined('ABSPATH') || exit;

require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/constants.php';

// Load all functions with the '.php' extension
load_files_from_directory({NAMESPACE}_PATH . 'functions/', false);

