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
class ExtDiamond_Proxy_ExtReference extends ExtDiamond_Proxy_Reference
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

	public function getItemAt($i)
	{
		$class = get_class($this);
		return new $class($this->selenium, $this->startPoint, $this->getNextPath("items.items[$i]"));
	}

	/**
	 * Reference converter
	 *
	 * It 'casts' the object into a more specified widget-related class.
	 * Please note that there are no checks about what you cast: you can
	 * transform a window into a grid getting errors!
	 *
	 * @remember Switch the brain on before the use.
	 * @param string $type
	 * @return ExtDiamond_Proxy_JsReference
	 */
	public function handleAs($type)
	{
		$class = 'ExtDiamond_Widget_' . $type . 'Reference';

		if (class_exists($class)) {
			return new $class($this->selenium, $this->startPoint, $this->internalPath);
		}

		throw new Exception("Widget `$type` not found.");
	}

}