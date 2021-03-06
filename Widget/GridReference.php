<?php

/**
 * Ext.grid.GridPanel component reference.
 *
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
 * @package ExtDiamond
 * @subpackage Widget
 */
class ExtDiamond_Widget_GridReference extends ExtDiamond_Proxy_ExtReference
{

	/**
	 * Wait until grid metadata are loaded.
	 * If your grid is not supposed to exchange metadata with a server,
	 * just don't use this!
	 *
	 * @param int $timeout [ms]
	 * @return ExtDiamond_Widget_GridReference
	 */
	public function waitForMetaData($timeout = 10000)
	{
		$condition = "window.Ext.getCmp('{$this->getProperty('id')}').hasMeta";
		$this->selenium->waitForCondition($condition, $timeout);

		return $this;
	}

	/**
	 * Get the reference to the row at the specified index.
	 *
	 * @param int $i Zero based index row
	 * @return ExtDiamond_Widget_GridRowReference
	 */
	public function getRow($i)
	{
		$js =  "if (window.Ext.getCmp('{$this->id}').view.getRow($i).id == '') {
					window.Ext.getCmp('{$this->id}').view.getRow($i).id = window.Ext.id();
				}";
		$id = $this->selenium->getEval($js);

		return new ExtDiamond_Widget_GridRowReference($this->selenium, '#' . $id);
	}

	/**
	 * Checks if the grid has the given column.
	 *
	 * @param string $name
	 * @return ExtDiamond_Widget_GridReference
	 */
	public function assertHasColumn($name)
	{
		$index = $this->colModel->findColumnIndex($name);
		$this->selenium->assertTrue($index != -1, "Column '$name' not found in the grid.");

		return $this;
	}

}
