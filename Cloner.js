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

// YOU DO NOT NEED TO INCLUDE THIS FILE BY HAND IN YOUR PAGES
// IT WILL BE MAGICALLY LOADED BY EXTDIAMOND!

window.ExtDiamond = {};

window.ExtDiamond.cloner = function(obj)
{
	if (obj == null || typeof(obj) != 'object') return obj;

	var type, ret = {};

    for (var key in obj) {
		type = typeof obj[key];
		switch (type) {
			case 'boolean':
			case 'string':
			case 'number':
				ret[key] = obj[key]; // value
				break;
			case 'object':
			case 'function':
			case 'array':
				ret[key] = [type]; // type only, to avoid infine loops!
				break;
		}
	}

    return JSON.stringify(ret);
};

window.ExtDiamond.fireEvent = function(obj, evt)
{
	var fireOnThis = obj;
	if (document.createEvent) {
		var evObj = document.createEvent("MouseEvents");
		evObj.initEvent(evt, true, false);
		fireOnThis.dispatchEvent(evObj);
	}
	else if (document.createEventObject) {
		fireOnThis.fireEvent("on" + evt);
	}
}

window.ExtDiamond.fcalls = {};
