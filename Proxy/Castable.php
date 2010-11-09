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
class ExtDiamond_Proxy_Castable extends ExtDiamond_Proxy_Reference
{

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
		// search in user-defined namespaces
		foreach ($this->selenium->getWidgetNamespaces() as $namespace) {
			if ($this->widgetExists($namespace, $type)) {
				return $this->getWidgetReference($namespace, $type);
			}
		}

		// search in ExtDiamond classes
		if ($this->widgetExists('ExtDiamond_Widget_', $type)) {
			return $this->getWidgetReference('ExtDiamond_Widget_', $type);
		}

		// nothing found
		throw new ExtDiamond_Exception_WidgetNotFound("Widget `$type` not found.");
	}

	/**
	 * Check if the namespace has the given widget.
	 *
	 * @param string $namespace
	 * @param string $widget
	 * @return bool
	 */
	protected function widgetExists($namespace, $widget)
	{
		$class = $namespace . $widget . 'Reference';
		return @class_exists($class);
	}

	/**
	 * Gets a reference of a widget in the given namespace.
	 *
	 * @param string $namespace
	 * @param string $widget
	 * @return ExtDiamond_Proxy_JsReference
	 */
	protected function getWidgetReference($namespace, $widget)
	{
		$class = $namespace . $widget . 'Reference';
		return new $class($this->selenium, '#' . $this->id);
	}

}
