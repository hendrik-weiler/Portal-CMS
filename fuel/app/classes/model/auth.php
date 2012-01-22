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
class model_auth
{

	public static $user;

	public static function check()
	{
		$session = Session::get('session_id');

		$result = false;

		$user = model_db_accounts::find('first',array(
			'where' => array(
				'session'=>$session
			)
		));

		if(!empty($user))
		{
			$result = true;
			self::$user = $user;
		}

		return $result;
	}

	public static function check_startup()
	{
		if(!self::check())
			Response::redirect('admin');

		Config::set('language',self::$user['language']);

		Lang::load('admin');
	}

	public static function logout()
	{
		$result = false;

		if(!empty(self::$user))
		{
			self::$user->session = 'logout_' . md5('logout' . $user->username . $user->session);
			self::$user->save();
			Session::destroy();

			$result = true;
		}

		return $result;
	}
}