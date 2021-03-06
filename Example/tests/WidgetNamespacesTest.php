<?php

class Vendor_TestCase extends ExtDiamond_TestCase
{
	public function setUp()
	{
		parent::setUp();
		$this->addWidgetNamespace('Vendor_Widget_');
	}
}

class Vendor_Widget_ExampleComponentReference extends ExtDiamond_Proxy_ExtReference
{
}


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
class WidgetNamespacesTest extends Vendor_TestCase
{

	protected $url = 'http://extdiamond.local/firewall.html';

	public function testCanUseExternalComponents()
	{
		$window = $this->getComponentByClass('firewall-window')->handleAs('ExampleComponent');

		$this->assertInstanceOf('Vendor_Widget_ExampleComponentReference', $window);
	}

	public function testUsesDefaultComponents()
	{
		$window = $this->getComponentByClass('firewall-window')->handleAs('Grid');

		$this->assertInstanceOf('ExtDiamond_Widget_GridReference', $window);
	}

	public function testCannotUseUnexistingExternalComponents()
	{
		try {
			$window = $this->getComponentByClass('firewall-window')->handleAs('Dummy');
			$this->fail('We expected an exception to be thrown.');
		} catch (ExtDiamond_Exception_WidgetNotFound $e) {
		}
	}

}
