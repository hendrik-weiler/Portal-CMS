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
class model_shop_order extends model_shop_countries
{
	private static function _convert_cart()
	{
		$new_cart = array();

		$cart = Session::get('cart');

		foreach ($cart as $article) 
		{
			$new_cart[] = array(
				'amount' => $article['amount'],
				'text' => $article['article']->get_label(),
				'unit_price' => $article['article']->get_price(),
				'total_price' =>	$article['article']->get_price_by_amount($article['amount'])
			);
		}

		return $new_cart;
	}

	public static function show_shop_step_1()
	{
		$data = array();

		$data['in_step'] = 1;

		$data['country_list'] = static::$countries;

		$val = Validation::forge('my_validation');
		$val->add_field('email', Input::post('email'), 'required|match_pattern[#\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b#i]');
		$val->add_field('first_name', Input::post('first_name'), 'required');
		$val->add_field('last_name', Input::post('last_name'), 'required');
		$val->add_field('street', Input::post('street'), 'required');
		$val->add_field('zip_code', Input::post('zip_code'), 'required|match_pattern[#[0-9]+#i]');
		$val->add_field('country', Input::post('country'), 'required');
		$val->add_field('location', Input::post('location'), 'required');

		// run validation on just post
		$errors = array();
		$errors['email_error'] = false;
		$errors['location_error'] = false;
		$errors['first_name_error'] = false;
		$errors['last_name_error'] = false;
		$errors['street_error'] = false;
		$errors['zip_code_error'] = false;
		$errors['country_error'] = false;
	  
		if(Session::get('cart') == null || Session::get('cart') === array())
		{
			Response::redirect(model_generator_preparer::$lang . '/cart/overview');
		}

		if(Session::get('delivery_address') == null)
		{
	    Session::set('delivery_address', array(
	    	'email' => '',
	    	'company' => '',
	    	'first_name' => '',
	    	'last_name' => '',
	    	'street' => '',
	    	'zip_code' => '',
	    	'country' => '',
	    	'phone' => '',
	    	'location' => '',
	    	'payment_method' => ''
	    ));
		}


	  $data += Session::get('delivery_address');

	  if(Input::post('back')) 
	  {
	  	Response::redirect(model_generator_preparer::$lang . '/cart/overview');
	  }	

	  if(Input::post('next')) 
	  {

			if ($val->run())
			{
			    Session::set('delivery_address', array(
			    	'email' => Input::post('email'),
			    	'company' => Input::post('company'),
			    	'first_name' => Input::post('first_name'),
			    	'last_name' => Input::post('last_name'),
			    	'street' => Input::post('street'),
			    	'zip_code' => Input::post('zip_code'),
			    	'country' => Input::post('country'),
			    	'phone' => Input::post('phone'),
			    	'location' => Input::post('location'),
			    	'payment_method' => Input::post('payment_method')
			    ));

			    Session::set('order_step1_made',true);

			    Response::redirect(model_generator_preparer::$lang . '/cart/step/2');
			}
			else
			{
			  foreach($val->errors() as $error) {
			    $errors[$error->field->name . '_error'] = true;
			  }

		    Session::set('delivery_address', array(
		    	'email' => Input::post('email'),
		    	'company' => Input::post('company'),
		    	'first_name' => Input::post('first_name'),
		    	'last_name' => Input::post('last_name'),
		    	'street' => Input::post('street'),
		    	'zip_code' => Input::post('zip_code'),
		    	'country' => Input::post('country'),
		    	'phone' => Input::post('phone'),
		    	'location' => Input::post('location'),
		    	'payment_method' => Input::post('payment_method')
		    ));

		    $data['email'] = Input::post('email');
			  $data['company'] = Input::post('company');
			  $data['first_name'] = Input::post('first_name');
			  $data['last_name'] = Input::post('last_name');
			  $data['street'] = Input::post('street');
			  $data['zip_code'] = Input::post('zip_code');
			  $data['country'] = Input::post('country');
			  $data['phone'] = Input::post('phone');
			  $data['location'] = Input::post('location');
			  $data['payment_method'] = Input::post('payment_method');

			  Session::set('order_step1_made',false);

			}

		}
		$data['errors'] = $errors;

    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/shop_order_step1.php'))
      return View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/shop_order_step1.php',$data);
		else
      return View::factory('public/template/shop_order_step1',$data);
	}

	public static function show_shop_step_2()
	{
		$data = array();

		$data['in_step'] = 2;

		if(Session::get('order_step1_made') == false) 
		{
			Response::redirect(model_generator_preparer::$lang . '/cart/step/1');
		}

		if(Session::get('cart') == null || Session::get('cart') === array())
		{
			Session::set('order_step2_made',false);
			Response::redirect(model_generator_preparer::$lang . '/cart/overview');
		}

	  if(Input::post('back')) 
	  {
	  	Session::set('order_step2_made',false);
	  	Response::redirect(model_generator_preparer::$lang . '/cart/step/1');
	  }	

	  if(Input::post('confirm')) 
	  {
	  	Session::set('order_step2_made',true);

	  	$converted_cart = static::_convert_cart();

	  	$summary_prices = Session::get('cart_summary_prices');

	  	$delivery_address = Session::get('delivery_address');

	  	$order = new model_db_order;
	  	$order->delivery_address = json_encode($delivery_address);
	  	$order->cart = json_encode($converted_cart);
	  	$order->summary_prices = json_encode($summary_prices);
	  	$order->canceled = 0;
	  	$order->accept = 0;
	  	$order->save();

	  	$mail = new model_shop_invoice_mail(model_db_order::find('last')->id);
	  	$mail->send();

			Response::redirect(model_generator_preparer::$lang . '/cart/step/3');
	  }

	  $data += Session::get('delivery_address');

    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/shop_order_step2.php'))
      return View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/shop_order_step2.php',$data);
		else
      return View::factory('public/template/shop_order_step2',$data);
	}

	public static function show_shop_step_3()
	{
		$data = array();

		$data['in_step'] = 3;

		if(Session::get('order_step2_made') == false) 
		{
			Response::redirect(model_generator_preparer::$lang . '/cart/step/2');
		}

		if(Session::get('cart') == null || Session::get('cart') === array())
		{
			Response::redirect(model_generator_preparer::$lang . '/cart/overview');
		}

		Session::destroy();

    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/shop_order_step3.php'))
      return View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/shop_order_step3.php',$data);
		else
      return View::factory('public/template/shop_order_step3',$data);
	}
}