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
class model_db_order extends Orm\Model
{

	protected static $_table_name = 'order';

	protected static $_properties = array('id', 'delivery_address', 'cart', 'summary_prices','accept','canceled','created_at');

	private function _0000_nr($number)
	{
		$count = count($number);

		while ($count < 4) {
			$number = '0' . $number;
			$count++;
		}

		return $number;
	}

	public function get_order_nr()
	{
		$format = model_db_option::getKey('order_format');

		return str_replace(array(
			'{year}',
			'{nr}',
			'{jahr}',
			'{nummer}',
			'{0000-nr}',
		), array(
			date('Y', time($this->created_at)),
			$this->id,
			date('Y', time($this->created_at)),
			$this->id,
			$this->_0000_nr($this->id),
		), $format->value);
	}

	public function get_delivery_address()
	{
		return Format::forge($this->delivery_address,'json')->to_array();
	}

	public function get_cart()
	{
		return Format::forge($this->cart,'json')->to_array();
	}

	public function get_summary_prices()
	{
		return Format::forge($this->summary_prices,'json')->to_array();
	}

}