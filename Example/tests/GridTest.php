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

class GridTest extends ExtDiamond_TestCase
{

	protected $url = 'http://extdiamond.local/firewall.html';

	public function testWindowHasTheDesiredStructure()
	{
		$window = $this->getComponentByClass('firewall-window')->handleAs('Window');

		$this->assertEquals('My Firewall', $window->title,
			'The window title is not what the customer asked');
		$this->assertEquals(1, $window->getItemsCount(),
			'The window should contain just one item');
	}

	public function testGridHasRecords()
	{
		$grid = $this->getComponentByClass('firewall-window')
					 ->getItemAt(0)->handleAs('Grid');

		$this->assertTrue($grid->store->totalLength > 0,
			'The grid store is empty. Empty fixtures?');

		$firstRow = $grid->getRow(3);
		$this->assertApplicationNameIsValid($firstRow->getTextOfCell(0));
	}

	protected function assertApplicationNameIsValid($applicationName)
	{
		preg_match('/\.exe|\.dll|\.bin/', $applicationName, $matches);

		if (count($matches) == 0) {
			$this->fail('Executable extension not found in application name');
		}
	}

	

}