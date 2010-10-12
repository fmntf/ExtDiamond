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
class ExtDiamond_Widget_GridRowReference extends ExtDiamond_Proxy_DomReference
{

	/**
	 * Click the row
	 *
	 * @return ExtDiamond_Widget_GridRowReference
	 */
	public function click()
	{
		$this->selenium->generateEvent($this->startPoint, 'mousedown');
		return $this;
	}

	/**
	 * Find out if this row is actually selected in the grid or not
	 * 
	 * @return bool
	 */
	public function isSelected()
	{
		return in_array('x-grid3-row-selected', $this->classList->getProperties());
	}

	/**
	 * Get the text contained in the cell at the specified column index.
	 *
	 * @param int $colIndex Zero based index
	 * @return string
	 */
	public function getTextOfCell($colIndex)
	{
		$root = "window.Ext.query('{$this->startPoint} td')[$colIndex]";
		$col = "window.Ext.query('div', $root)[0].innerHTML";

		return $this->selenium->getEval($col);
	}

}
