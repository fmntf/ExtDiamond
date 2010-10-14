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
class ExtDiamond_Proxy_DomReference extends ExtDiamond_Proxy_Reference
{

	protected function getPath()
	{
		$startPoint = "window.Ext.query('{$this->startPoint}')[0]";

		if ($this->internalPath) {
			return $startPoint . '.' . $this->internalPath;
		} else {
			return $startPoint;
		}
	}

	/**
	 * Get the (ID) reference to an elment, which can be found via CSS selector.
	 * 
	 * @param string $cssSelector
	 * @param int $index Which element to get in all the matches
	 * @return string|null ID of found element. Null if nothing found.
	 */
	public function getReferenceTo($cssSelector, $index=0)
	{
		$root = "window.Ext.query('{$this->startPoint}')[0]";
		$element = "window.Ext.query('$cssSelector', $root)[$index]";

		$isUndefined = $this->selenium->getEval("($element === undefined)");
		if ($isUndefined == 'true') {
			return false;
		}

		$js =  "if ($element.id == '') {
					$element.id = window.Ext.id();
				}";
		return $this->selenium->getEval($js);
	}

}