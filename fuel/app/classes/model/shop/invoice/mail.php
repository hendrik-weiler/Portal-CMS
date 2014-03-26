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
 * @copyright  2012 Hendrik Weiler
 */
class model_shop_invoice_mail
{
	private $_email;

	private $_delivery_address;

	private $_cart;

	private $_summary_prices;

	private $_order;

	private $_lang;

	private function _generate_body($mail=false, $lang)
	{
		$data = array();

		$data += $this->_delivery_address;
		$data['cart'] = $this->_cart;
		$data['summary_prices'] = $this->_summary_prices;

		$data['order'] = $this->_order;

		$data['setting_first_name'] = model_db_option::getKey('first_name')->value;
		$data['setting_last_name'] = model_db_option::getKey('last_name')->value;
		$data['setting_tax_id'] = model_db_option::getKey('tax_id')->value;
		$data['setting_phone'] = model_db_option::getKey('phone')->value;
		$data['setting_street'] = model_db_option::getKey('street')->value;
		$data['setting_zip_code'] = model_db_option::getKey('zip_code')->value;
		$data['setting_location'] = model_db_option::getKey('location')->value;
		$data['setting_company'] = model_db_option::getKey('company')->value;
		$data['setting_manager'] = model_db_option::getKey('manager')->value;
		$data['setting_email'] = model_db_option::getKey('email')->value;
		$data['setting_ust_id'] = model_db_option::getKey('ust_id')->value;
		$data['setting_local_court'] = model_db_option::getKey('local_court')->value;
		$data['setting_commercial_register_number'] = model_db_option::getKey('commercial_register_number')->value;

		$data['setting_bank_name'] = model_db_option::getKey('bank_name')->value;
		$data['setting_account_number'] = model_db_option::getKey('account_number')->value;
		$data['setting_bank_sort_code'] = model_db_option::getKey('bank_sort_code')->value;

		$data['setting_country'] = model_db_option::getKey('country')->value;
		$data['setting_fax'] = model_db_option::getKey('fax')->value;

		$data['is_mail'] = $mail;

		if($mail) {
			$data['image'] = 'cid:logo';
		}
		else {
			$invoice_logo = model_db_option::getKey('invoice_logo')->value;
			$data['image'] = Uri::create('uploads/shop/logo/' . $invoice_logo);
		}		

		Lang::load('shop');

    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/shop_email_invoice_' . $lang . '.php'))
      return View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/shop_email_invoice_' . $lang . '.php',$data);
		else
      return View::factory('public/template/shop_email_invoice_en',$data);
	}

	public function __construct($order_id, $lang)
	{

		$this->_email = Email::forge();

		$this->_lang = $lang;

		$order = model_db_order::find($order_id);
		$this->_order = $order;
		$this->_delivery_address = $order->get_delivery_address();
		$this->_cart = $order->get_cart();
		$this->_summary_prices = $order->get_summary_prices();

		$email = model_db_option::getKey('email')->value;
		$name = model_db_option::getKey('company')->value;

		$this->_email->from($email, $name);
		$this->_email->cc($email , $name);

		$this->_email->to($this->_delivery_address['email'], $this->_delivery_address['first_name'] . ' ' . $this->_delivery_address['last_name']);		

		$title = $name . ' - ' . __('shop.invoice.title') . date(__('shop.invoice.time_format'),time($order->created_at));

		$this->_email->subject($title);

		$invoice_logo = model_db_option::getKey('invoice_logo')->value;

		if(!empty($invoice_logo)) {
			$this->_email->attach('uploads/shop/logo/' . $invoice_logo, true, 'cid:logo');
		}

		$this->_email->html_body($this->_generate_body(true, $lang));
	}

	public function show()
	{
		return $this->_generate_body(false, $this->_lang);
	}
	
	public function send()
	{
		try
		{
		    $this->_email->send();
		}
		catch(\EmailValidationFailedException $e)
		{
			print 'Validation Error:';
			print	$e->getMessage();
		    exit;
		}
		catch(\EmailSendingFailedException $e)
		{
			print 'Email Sending Error:';
			print	$e->getMessage();
		    exit;
		}
	}

}