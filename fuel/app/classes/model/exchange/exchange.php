<?php
/*
 * Portal Content Management System
 * Copyright (C) 2011  Hendrik Weiler
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
 * @author     Hendrik Weiler
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2011 Hendrik Weiler
 */
class model_exchange_exchange
{
	public function createDBStringFromModel($type, $model)
	{
        if(!is_object($model)) return $type . '#';

		$data = array();
		foreach ($model as $key => $value) {
			$data[$key] = $value;
		}

		return $type . '#' . base64_encode(json_encode($data));
	}

	public function decodeDBString($dbString)
	{
		$data = explode('#', $dbString);

		$row = json_decode($data[1]);

		return array(
			'type' => $data[0],
			'model' => $row
		);
	}
}