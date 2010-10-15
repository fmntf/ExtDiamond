<?php

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

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

abstract class ExtDiamond_TestCase extends PHPUnit_Extensions_SeleniumTestCase
{

	/**
	 * URL of the page which will contain ExtJS components.
	 * 
	 * @var string
	 */
	protected $url;

	/**
	 * List of namespaces where to find widgets.
	 * 
	 * @var array
	 */
	protected $widgetNamespaces = array();

	/**
	 * Set default options
	 */
	public function setUp()
	{
		if (!isset($this->url)) {
			throw new Exception('You must define the test case URL.');
		}

		$this->setBrowser('*googlechrome');
        $this->setBrowserUrl($this->url);
		$this->start();
		$this->openAndWait($this->url);

		$this->injectClientCode();
	}

	/**
	 * Get an Ext Component, by the class assigned to it.
	 *
	 * @see http://dev.sencha.com/deploy/dev/docs/output/Ext.Component.html#Ext.Component-cls
	 * @param string $class CSS class
	 * @return ExtDiamond_Proxy_ExtReference
	 */
	protected function getComponentByClass($class)
	{
		$js = "window.Ext.query('.$class')[0]";
		if ($this->getEval($js) == 'null') {
			throw new ExtDiamond_Exception_ComponentNotFound("Component with class '$class' not found.");
		}

		return new ExtDiamond_Proxy_ExtReference($this, ".$class");
	}

	/**
	 * Get a DOM reference by the given CSS selector.
	 * Tip: once you get a DOM reference, you can refine your search using the
	 * getReferenceTo() method.
	 *
	 * @param string $css
	 * @return ExtDiamond_Proxy_DomReference
	 */
	protected function getDomBySelector($css)
	{
		return new ExtDiamond_Proxy_DomReference($this, $css);
	}

	/**
	 * Get any variabile of the client by name
	 *
	 * @param string $name
	 * @return ExtDiamond_Proxy_VarReference
	 */
	protected function getVariableByName($name)
	{
		return new ExtDiamond_Proxy_VarReference($this, 'window.' . $name);
	}

	/**
	 * Pause the Selenium execution, giving to the ability to investigate in your
	 * page with your favourite JS debugger / DOM inspector.
	 */
	protected function waitForever()
	{
		$this->waitForCondition("false", 30000000);
	}

	/**
	 * Fires the event on the first element that matches $cssSelector.
	 * This can be useful when the Selenium click() function is not working.
	 * <code>
	 * $this->generateEvent('div.button', 'mousedown');
	 * </code>
	 *
	 * @todo Find out why click() sometimes is not working, and remove this function.
	 * @param string $cssSelector  CSS selector for Ext.query()
	 * @param string $event        DOM event (all lowercase, without 'on' prefix)
	 */
	protected function generateEvent($cssSelector, $event)
	{
		$js = "window.ExtDiamond.fireEvent(window.Ext.query('$cssSelector')[0], '$event');";

		$this->getEval($js);
	}

	/**
	 * Inject the object cloner into the test browser.
	 */
	private function injectClientCode()
	{
		$js = file_get_contents(dirname(__FILE__) . '/Client.js');
		$this->getEval($js);
	}

	/**
	 * Adds a new namespace in the list of widgets paths.
	 *
	 * @param string $namespace NS with trailing underscore, like: Your_Path_
	 */
	public function addWidgetNamespace($namespace)
	{
		$this->widgetNamespaces[] = $namespace;
	}

	/**
	 * Gets all namespaces where to find widgets.
	 * 
	 * @return array
	 */
	public function getWidgetNamespaces()
	{
		return $this->widgetNamespaces;
	}

}
