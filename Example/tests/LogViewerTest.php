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

class LogViewerTest extends ExtDiamond_TestCase
{

	protected $url = 'http://extdiamond.local/logviewer.html';

	public function testOnPageLoadBothDateAndLogsAreEmpty()
	{
		$form = $this->getComponentByClass('logviewer');

		$this->assertEquals('', $form->datepicker->getValue());
		$this->assertEquals('', $form->eventlist->getValue());
	}

	public function testChangingADateWillReloadLogs()
	{
		$form = $this->getComponentByClass('logviewer');
		$date = $form->datepicker->handleAs('DateField');

		$date->pick('03-23-2010');
		$logs = $form->eventlist->getValue();
		$this->assertTrue((bool)strstr($logs, '2010-03-23'),
			'The logs seems to be not about the picked date');

		$date->pick('03-24-2010');
		$logs = $form->eventlist->getValue();
		$this->assertTrue((bool)strstr($logs, '2010-03-24'),
			'The date change had no effect of logs');
	}

	public function testCanDestroyEverything()
	{
		$form = $this->getComponentByClass('logviewer');
		$button = $form->buttons->getArrayItemAt(0);

		$this->click('css=#' . $button->id);

		$this->setExpectedException('ExtDiamond_Exception_ComponentNotFound');
		$form = $this->getComponentByClass('logviewer');
	}

}