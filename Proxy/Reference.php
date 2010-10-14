<?php

/**
 * ExtDiamond - Easy testing for ExtJS
 * Copyright (C) 2010 Francesco Montefoschi <francesco.monte@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.html  GNU GPL 3.0
 */
abstract class ExtDiamond_Proxy_Reference
{

	/**
	 * SeleniumTestCase reference
	 * @var PHPUnit_Extensions_SeleniumTestCase
	 */
	protected $selenium;

	/**
	 * DOM path used to reach the javascript object
	 * @var string
	 */
	protected $startPoint;

	/**
	 * Object path to reach the subobjects
	 * @var string
	 */
	protected $internalPath;

	/**
	 * Scanned object properties
	 * @var array
	 */
	protected $properties;

	/**
	 * Object Reference - get an identifiable element.
	 *
	 * As start point you should give a string that will return a JS object, like:
	 *   Ext.query(myselector)[0]
	 *
	 * Internal path is used to navigate the found object. For example,
	 *   store.baseParams
	 *
	 * if applied to a grid object will return it's store base params object.
	 * Another example, a window which contains a grid as first item:
	 *
	 *   $window = new Reference($this, "Ext.query('.window')[0]");
	 *   $store = $window->items->items->itemAt(0)->store;
	 *
	 * In this case, you will get grid's store in the $store variable, and you
	 * will be able to test all the properties inside it, for example:
	 *   $this->assertFalse($store->isDestroyed);
	 *
	 * It's not possible to test an ExtJS application and knowing anything about
	 * the DOM, but as you can see in this case, you only need something to start
	 * from (the start point) and never worry about it again.
	 *
	 * @param PHPUnit_Extensions_SeleniumTestCase $selenium
	 * @param string $startPoint
	 * @param string $internalPath
	 */
	public function  __construct(PHPUnit_Extensions_SeleniumTestCase $selenium,
		$startPoint, $internalPath = ''
	) {
		$this->selenium = $selenium;
		$this->startPoint = $startPoint;
		$this->internalPath = $internalPath;
	}


	/**
	 * Get the value of a property
	 *
	 * @param string $name Property name
	 * @return string|ExtDiamond_Proxy_JsReference Value or subreference
	 */
	public function __get($name)
	{
		return $this->getProperty($name);
	}

	/**
	 * Get the value of a property
	 *
	 * @param string $name Property name
	 * @param bool $safe True to prevent exception throwing
	 * @return string|ExtDiamond_Proxy_JsReference Property value or subreference
	 * Returns null if the property is not found and safe is true.
	 */
	public function getProperty($name, $safe = false)
	{
		$this->assurePropertiesLoaded();

		if (!array_key_exists($name, $this->properties)) {
			if ($safe) return null;
			throw new Exception("Property '$name' not found!");
		}

		$value = $this->properties[$name];
		if (!is_array($value)) {
			return $value;
		}

		if ($value[0] == 'function') {
			throw new Exception("Cannot get a function as property.");
		}

		$class = get_class($this);
		return new $class($this->selenium, $this->startPoint, $this->getNextPath($name));
	}

	/**
	 * Get all the loaded properties.
	 */
	public function getProperties()
	{
		$this->assurePropertiesLoaded();
		return $this->properties;
	}

	/**
	 * Get only properties with scalar values.
	 */
	public function getScalarProperties()
	{
		$this->assurePropertiesLoaded();
		$scalars = array();

		foreach ($this->properties as $property => $value) {
			if (!is_array($value)) {
				$scalars[$property] = $value;
			}
		}

		return $scalars;
	}

	/**
	 * Get fresh object properties
	 */
	public function refresh()
	{
		$js  = "window.ExtDiamond.cloner(" . $this->getPath() . ")";
		$result = $this->selenium->getEval($js);

		$properties = json_decode($result, true);
		if ($properties === null) {
			throw new Exception('Could not get object properties! Check the path used to reach the object.');
		}

		$this->properties = $properties;
	}

	/**
	 * Loads the properties iif we did not load them yet.
	 */
	protected function assurePropertiesLoaded()
	{
		if ($this->properties === null) {
			$this->refresh();
		}
	}

	/**
	 * Adds a new level to the object navigation pointer.
	 *
	 * @param string $name
	 * @return string
	 */
	protected function getNextPath($name)
	{
		if ($this->internalPath == '') {
			return $name;
		}

		return $this->internalPath . '.' . $name;
	}

	/**
	 * Calls a Javascript function.
	 *
	 * @param string $f Method name
	 * @param array $arguments
	 * @return mixed|ExtDiamond_Proxy_VarReference Scalar value or variable reference
	 */
	public function __call($f, array $arguments)
	{
		$args = $this->getArgumentsAsString($arguments);
		$unique = 'ExtDiamond.fcalls.' . $this->getId();

		$js = "window.$unique = " . $this->getPath() . ".$f($args);

		window.Ext.isPrimitive(window.$unique);";

		$isPrimitive = $this->selenium->getEval($js);

		if ($isPrimitive == 'false') {
			return new ExtDiamond_Proxy_VarReference($this->selenium, 'window.' . $unique);
		}

		$js = "window.Ext.isBoolean(window.$unique);";
		$isBoolean = $this->selenium->getEval($js);
		$value = $this->selenium->getEval("window.$unique");

		if ($isBoolean == 'true') {
			return $value == 'true';
		} else {
			return $value;
		}
	}

	/**
	 * Transforms an array of arguments in a string.
	 * Strings will be escaped, unless wrapped by an ExtDiamond_UnescapedArgument
	 * object.
	 *
	 * @param array $arguments
	 * @return string
	 */
	protected function getArgumentsAsString(array $arguments)
	{
		$args = array();

		foreach ($arguments as $argument) {

			if ($argument instanceof ExtDiamond_UnescapedArgument) {
				$arg = $argument;
			} else {
				switch (gettype($argument)) {
					case 'boolean':
						$arg = ($argument===true) ? "true" : "false";
						break;

					case 'integer':
					case 'double':
						$arg = $argument;
						break;

					case 'string':
						$arg = "'$argument'";
						break;

					case 'NULL':
						$arg = "null";
						break;

					default:
						throw new Exception('You cannot pass non-scalar values to functions.');
				}
			}

			$args[] = $arg;
		}

		return implode(', ', $args);
	}

	/**
	 * Get an unique string in the format "gen123".
	 * 
	 * @return string
	 */
	public function getId()
	{
		$gen = explode('ext-', $this->selenium->getEval('window.Ext.id();'));
		return $gen[1];
	}

}
