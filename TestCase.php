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

		$this->injectCloner();
	}

	/**
	 * Get an Ext Component, by the class assigned to it.
	 *
	 * @see http://dev.sencha.com/deploy/dev/docs/output/Ext.Component.html#Ext.Component-cls
	 * @param string $class CSS class
	 * @return ExtDiamond_Proxy_Reference
	 */
	protected function getComponentByClass($class)
	{
		return new ExtDiamond_Proxy_ExtReference($this, ".$class");
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
	private function injectCloner()
	{
		$js = file_get_contents(dirname(__FILE__) . '/Cloner.js');
		$this->getEval($js);
	}

}
