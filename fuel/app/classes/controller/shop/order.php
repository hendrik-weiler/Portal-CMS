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
class Controller_Shop_Order extends Controller
{

	private $data = array();

	private $id;

	public function before()
	{
		model_auth::check_startup();
		$this->data['title'] = 'Admin - Shop';
		$this->id = $this->param('id');

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!model_permission::currentLangValid())
			Response::redirect('admin/logout');

		Lang::load('tasks');
	}

	public function action_accept()
	{
		$id = $this->param('id');
		$order = model_db_order::find($id);
		$order->accept = 1;
		$order->save();

		Response::redirect('admin/shop/orders/display/' . $id);
	}

	public function action_cancel()
	{
		$id = $this->param('id');
		$order = model_db_order::find($id);
		$order->canceled = 1;
		$order->save();

		Response::redirect('admin/shop/orders/display/' . $id);
	}

	public function action_display()
	{
		$data = array();

		$order = model_db_order::find($this->param('id'));

		Lang::load('shop');

		$data += $order->get_delivery_address();
		$data['cart'] = $order->get_cart();
		$data['summary_prices'] = $order->get_summary_prices();
		$data['canceled'] = $order->canceled;
		$data['accept'] = $order->accept;
		$data['order_id'] = $order->id;
		
		$this->data['content'] = View::factory('admin/shop/columns/orders_display',$data);
	}

	public function action_display_mail()
	{
		$data = array();

		$mail = new model_shop_invoice_mail($this->param('id'));

		$response = Response::forge($mail->show());
		$response->set_header('Content-Type','text/html; charset=utf-8');

		return $response;
	}

	public function action_index()
	{
		$data = array();

		$data['orders'] = model_db_order::find('all',array(
			'order_by' => array('created_at'=>'DESC')
		));
		
		$this->data['content'] = View::factory('admin/shop/columns/orders',$data);
	}

	public function after($response)
	{
		$this->response->body = View::factory('admin/shop',$this->data);
	}
}