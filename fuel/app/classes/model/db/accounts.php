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
class model_db_accounts extends Orm\Model
{

	protected static $_table_name = 'accounts';

	protected static $_properties = array('id', 'username', 'password', 'session','language','admin','permissions');

	public static function getCol($session,$col)
	{
		$account = model_db_accounts::find('first',array(
		  'where' => array('session' => $session),
		));

		return $account->$col;
	}

	public static function get_system_language()
	{
		$account = model_db_accounts::find('first',array(
		  'where' => array('session' => Session::get('session_id')),
		));

		return str_replace(array('/','\\'), '', $account->language);
	}
}