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
class Controller_Shop_Settings extends Controller
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

	public function action_edit()
	{
		if(Input::post('edit_settings') != '')
		{
			$rates = model_db_option::getKey('exchange_rates');
			$logo = model_db_option::getKey('invoice_logo');
			$shipping_costs = model_db_option::getKey('shipping_costs');

			$order_format = model_db_option::getKey('order_format');
			$order_format->value = Input::post('order_format');
			$order_format->save();

			$first_name = model_db_option::getKey('first_name');
			$first_name->value = Input::post('first_name');
			$first_name->save();

			$last_name = model_db_option::getKey('last_name');
			$last_name->value = Input::post('last_name');
			$last_name->save();

			$tax_id = model_db_option::getKey('tax_id');
			$tax_id->value = Input::post('tax_id');
			$tax_id->save();

			$phone = model_db_option::getKey('phone');
			$phone->value = Input::post('phone');
			$phone->save();

			$street = model_db_option::getKey('street');
			$street->value = Input::post('street');
			$street->save();

			$zip_code = model_db_option::getKey('zip_code');
			$zip_code->value = Input::post('zip_code');
			$zip_code->save();

			$location = model_db_option::getKey('location');
			$location->value = Input::post('location');
			$location->save();

			$payment_method_advance_payment = model_db_option::getKey('payment_method_advance_payment');
			$payment_method_advance_payment->value = Input::post('payment_method_advance_payment');
			$payment_method_advance_payment->save();

			$payment_method_invoice_payment = model_db_option::getKey('payment_method_invoice_payment');
			$payment_method_invoice_payment->value = Input::post('payment_method_invoice_payment');
			$payment_method_invoice_payment->save();

			$company = model_db_option::getKey('company');
			$company->value = Input::post('company');
			$company->save();

			$manager = model_db_option::getKey('manager');
			$last_name->value = Input::post('last_name');
			$last_name->save();

			$email = model_db_option::getKey('email');
			$email->value = Input::post('email');
			$email->save();

			$ust_id = model_db_option::getKey('ust_id');
			$ust_id->value = Input::post('ust_id');
			$ust_id->save();

			$local_court = model_db_option::getKey('local_court');
			$local_court->value = Input::post('local_court');
			$local_court->save();

			$bank_name = model_db_option::getKey('bank_name');
			$bank_name->value = Input::post('bank_name');
			$bank_name->save();

			$account_number = model_db_option::getKey('account_number');
			$account_number->value = Input::post('account_number');
			$account_number->save();

			$bank_sort_code = model_db_option::getKey('bank_sort_code');
			$bank_sort_code->value = Input::post('bank_sort_code');
			$bank_sort_code->save();

			$fax = model_db_option::getKey('fax');
			$fax->value = Input::post('fax');
			$fax->save();

			$country = model_db_option::getKey('country');
			$country->value = Input::post('country');
			$country->save();


			$labels = array();
			foreach ($_POST as $key => $value) {
				if(preg_match('#rates_#i', $key)) {
					$lang_prefix = explode('rates_', $key);
					$labels[$lang_prefix[1]] = str_replace(',', '.', $value);
				}
			}
			$rates->value = Format::forge($labels)->to_json();
			$rates->save();

			$labels = array();
			$_POST = Input::post();
			foreach ($_POST as $key => $value) {
				if(preg_match('#cost_summary_#i', $key)) {
					$lang_prefix = explode('cost_summary_', $key);
					$key = str_replace(',', '.', $_POST['cost_summary_' . $lang_prefix[1]]);
					$value = str_replace(',', '.', $_POST['cost_value_' . $lang_prefix[1]]);
					$labels[$key] = $value;
				}
			}

			$shipping_costs->value = Format::forge($labels)->to_json();
			$shipping_costs->save();

			if(!is_dir(DOCROOT . 'uploads/shop/logo'))
				File::create_dir(DOCROOT . 'uploads/shop/logo',0777);

			$config = array(
		    'path' => DOCROOT.'uploads/shop/logo',
		    'randomize' => true,
		    'auto_rename' => false,
		    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
			);
			Upload::process($config);

			if (Upload::is_valid())
			{
				Upload::save();
				foreach(Upload::get_files() as $file)
				{
					$resizeObj = new image\resize(DOCROOT . 'uploads/shop/logo/' . $file['saved_as']);

					$resizeObj -> resizeImage(200, 200, 'auto');
					$resizeObj -> saveImage(DOCROOT . 'uploads/shop/logo/' . $file['saved_as'], 100);

					if(!is_dir(DOCROOT . 'uploads/shop/logo/' . $logo->value) && file_exists(DOCROOT . 'uploads/shop/logo/' . $logo->value))
						File::delete(DOCROOT . 'uploads/shop/logo/' . $logo->value);

					$logo->value = $file['saved_as'];
					$logo->save();
				}
			}

			Response::redirect('admin/shop/settings');
		}

	}

	public function action_index()
	{
		$data = array();

		$rates = model_db_option::getKey('exchange_rates')->value;
		$rates = Format::forge($rates, 'json')->to_array();

		$data['rates'] = array();
		foreach (model_db_language::find('all') as $lang) {
			!isset($rates[$lang->prefix]) and $rates[$lang->prefix] = 1;
			empty($rates[$lang->prefix]) and $rates[$lang->prefix] = 1;
			$data['rates'][$lang->prefix] = number_format($rates[$lang->prefix], 2, ',', '');
		}

		$data['shipping_costs'] = model_db_option::getKey('shipping_costs')->value;
		$data['shipping_costs'] = Format::forge($data['shipping_costs'], 'json')->to_array();

		$data['invoice_logo'] = model_db_option::getKey('invoice_logo')->value;
		$data['order_format'] = model_db_option::getKey('order_format')->value;

		$data['first_name'] = model_db_option::getKey('first_name')->value;
		$data['last_name'] = model_db_option::getKey('last_name')->value;
		$data['tax_id'] = model_db_option::getKey('tax_id')->value;
		$data['phone'] = model_db_option::getKey('phone')->value;
		$data['street'] = model_db_option::getKey('street')->value;
		$data['zip_code'] = model_db_option::getKey('zip_code')->value;
		$data['location'] = model_db_option::getKey('location')->value;
		$data['company'] = model_db_option::getKey('company')->value;
		$data['manager'] = model_db_option::getKey('manager')->value;
		$data['email'] = model_db_option::getKey('email')->value;
		$data['ust_id'] = model_db_option::getKey('ust_id')->value;
		$data['local_court'] = model_db_option::getKey('local_court')->value;
		$data['commercial_register_number'] = model_db_option::getKey('commercial_register_number')->value;

		$data['payment_method_invoice_payment'] = model_db_option::getKey('payment_method_invoice_payment')->value;
		$data['payment_method_advance_payment'] = model_db_option::getKey('payment_method_advance_payment')->value;

		$data['bank_name'] = model_db_option::getKey('bank_name')->value;
		$data['account_number'] = model_db_option::getKey('account_number')->value;
		$data['bank_sort_code'] = model_db_option::getKey('bank_sort_code')->value;

		$data['country'] = model_db_option::getKey('country')->value;
		$data['fax'] = model_db_option::getKey('fax')->value;

		$data['country_list'] = model_shop_countries::$countries;
		
		$this->data['content'] = View::factory('admin/shop/columns/settings',$data);
	}

	public function after($response)
	{
		$this->response->body = View::factory('admin/shop',$this->data);
	}
}