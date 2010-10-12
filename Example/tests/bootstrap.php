<?php

/**
 * This file contains the autloading function only because this is an example.
 * You should use the autoloader of your own framework, like Zend_Loader.
 *
 * If you are not using a framework, you can still use this simple autoloading
 * strategy, but you must map class names with the PEAR convention.
 *
 * For example, the file Ext/Directory/MyGrid.php must contain just the class
 * Ext_Directory_MyGrid and nothing else.
 */


/**
 * Auto-load a class file. Do not call this directly!
 *
 * @param string $class
 */
function autoloader($class)
{
	if ($class == 'Tests') {
		throw new Exception('aa');
	}

	$path = dirname(__FILE__) . '/../../../' . str_replace('_', '/', $class) . '.php';

	if (is_file($path)) {
		require_once $path;
	}
}

spl_autoload_register('autoloader');
