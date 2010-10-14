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
class ExtDiamond_Widget_FieldReference extends ExtDiamond_Proxy_ExtReference
{

	/**
	 * Put the $value into the field.
	 *
	 * @param string $value
	 * @return ExtDiamond_Widget_FieldReference
	 */
	public function type($value)
	{
		$this->selenium->type('css=' . $this->startPoint, $value);

		return $this;
	}

	/**
	 * Clicks the parent container.
	 * In this way, the element will lose the focus.
	 *
	 * @return ExtDiamond_Widget_FieldReference
	 */
	public function blur()
	{
		$parent = $this->ownerCt->id;
		$this->selenium->clickAt('css=#' . $parent, '1,1');

		return $this;
	}

}