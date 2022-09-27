<?php

/**
 * Eckode theme functions file
 *
 * @package Eckode
 */

namespace Eckode;

/**
 * Constants
 */

// Runtime
define(__NAMESPACE__ . '\URL', trailingslashit(get_template_directory_uri()));

// Compile-time
const VER      = '0.0.1';
const PATH     = __DIR__ . '/';

/**
 * Auto-load classes
 */
if (file_exists(PATH . 'vendor/autoload.php')) {
	require(PATH . 'vendor/autoload.php');
} else {
	try {
		\spl_autoload_register(function ($class) {
			/** Only auto-load from within this directory */
			if (false === stripos($class, __NAMESPACE__)) {
				return;
			}

			$file_path = PATH . 'inc/classes/' . str_ireplace(__NAMESPACE__ . '\\', '', $class) . '.php';
			$file_path = str_replace('\\', DIRECTORY_SEPARATOR, $file_path);
			if (file_exists($file_path)) {
				include_once($file_path);
			}
		});
	} catch (\Exception $e) {
	}
}

/**
 * Utility/helper functions
 */
include_once PATH . 'inc/constants.php';
include_once PATH . 'inc/utils/boot.php';
include_once PATH . 'inc/utils/structures.php';
include_once PATH . 'inc/utils/enqueue.php';
include_once PATH . 'inc/utils/menu.php';
include_once PATH . 'inc/utils/rest.php';

/**
 * Hooks last
 */
include_once PATH . 'inc/hooks/assets.php';
include_once PATH . 'inc/hooks/init.php';
