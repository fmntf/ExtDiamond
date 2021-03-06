<?php

/**
 * Reference to ExtJS components.
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
 * @subpackage Reference
 */
class ExtDiamond_Proxy_ExtReference extends ExtDiamond_Proxy_Castable
{

	protected function getPath()
	{
		$startPoint = "window.Ext.getCmp(window.Ext.query('{$this->startPoint}')[0].id)";

		if ($this->internalPath) {
			return $startPoint . '.' . $this->internalPath;
		} else {
			return $startPoint;
		}
	}

	/**
	 * Get the i-th item of the component. Shorthand for items.items[i]
	 *
	 * @param int $i
	 * @return ExtDiamond_Proxy_Reference
	 */
	public function getItemAt($i)
	{
		$class = get_class($this);
		return new $class($this->selenium, $this->startPoint, $this->getNextPath("items.items[$i]"));
	}

}
