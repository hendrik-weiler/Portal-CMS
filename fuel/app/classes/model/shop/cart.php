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
class model_shop_cart extends model_db_article
{

	public static function calculate_tax_summary()
	{
		$warenkorb = Session::get('cart');
		empty($warenkorb) and $warenkorb = array();

		$summary = 0;

		for ($i=0; $i < count($warenkorb); $i++) 
		{
			$summary += (( $warenkorb[$i]['article']->get_price(0, true) - $warenkorb[$i]['article']->get_netto_price()) * $warenkorb[$i]['amount'] );
		}

		return $summary;
	}

	public static function calculate_summary($include_shipping=false)
	{
		$warenkorb = Session::get('cart');
		empty($warenkorb) and $warenkorb = array();

		$summary = 0;

		for ($i=0; $i < count($warenkorb); $i++) 
		{
			$summary += ( $warenkorb[$i]['article']->get_price(0, true) * $warenkorb[$i]['amount'] );
		}

		if($include_shipping) {
			$summary += static::calculate_shipping_costs(true,$summary);
		}

		return $summary;
	}

	public static function calculate_netto_summary()
	{
		$warenkorb = Session::get('cart');
		empty($warenkorb) and $warenkorb = array();

		$summary = 0;

		for ($i=0; $i < count($warenkorb); $i++) 
		{
			$summary += ( $warenkorb[$i]['article']->get_netto_price() * $warenkorb[$i]['amount'] );
		}

		return $summary;
	}

	public static function calculate_shipping_costs($get_summary_auto=false, $summary=0)
	{
		if(!$get_summary_auto) {
			$summary = static::calculate_summary();
		}

		$shipping_costs = model_db_option::getKey('shipping_costs')->value;
		$shipping_costs = Format::forge($shipping_costs, 'json')->to_array();

		$shipping_price = 0;

		foreach ($shipping_costs as $summary_condition => $value) {
			if($summary > $summary_condition) {
				$shipping_price = $value;
			}
		}

		return $shipping_price;
	}

	public static function add_item($article_id, $amount)
	{
		$article = static::find($article_id);

		$warenkorb = Session::get('cart');
		empty($warenkorb) and $warenkorb = array();

		$added = false;

		for ($i=0; $i < count($warenkorb); $i++) 
		{ 

			if($warenkorb[$i]['id'] === $article_id) 
			{
				$warenkorb[$i]['amount'] += $amount;
				$added = true;
			}
			
		}

		if(!$added)
		{
			$warenkorb[] = array(
				'id' => $article_id,
				'article' => $article,
				'amount' => $amount
			);
		}

		Session::set('cart', $warenkorb);
		Response::redirect(Uri::current());
	}

	public static function change_item($index, $value)
	{
		$cart = Session::get('cart');

		$cart[$index]['amount'] = $value;

		if($value == 0) {
			static::remove_item($index);
			$cart = Session::get('cart');
		}

		Session::set('cart',$cart);
	}

	public static function remove_item($index)
	{
		$cart = Session::get('cart');
		if(isset($cart[$index]))
			unset($cart[$index]);

		$cart = array_values($cart);

		Session::set('cart',$cart);
	}

	public static function render($supress_interaction=false)
	{
		$data = array();

		$article = new model_db_article;

		if(Input::post('shop_modify') != '' || Input::post('shop_order') != '')
		{
			foreach (Input::post() as $key => $value) {
				if(preg_match('#amount_#i', $key)) {
					$key = explode('amount_', $key);
					$key = $key[1];

					static::change_item($key, $value);
				}
			}
			if(Input::post('shop_order') != '') {
				Response::redirect(model_generator_preparer::$lang . '/cart/step/1');
			}
			else {
				Response::redirect(Uri::current());
			}
		}

		$data['cart_content'] = Session::get('cart');
		$data['cart_content'] == null and $data['cart_content'] = array();

		$data['summary'] = $article->get_price( model_shop_cart::calculate_summary(true) );
		$data['tax'] = $article->get_price( model_shop_cart::calculate_tax_summary() );
		$data['shipping_cost'] = $article->get_price( model_shop_cart::calculate_shipping_costs() );
		$data['netto'] = $article->get_price( model_shop_cart::calculate_netto_summary() );
		$data['brutto'] = $article->get_price( model_shop_cart::calculate_summary() );

		$data['shipping_costs_reached'] = ( model_shop_cart::calculate_summary() >= model_shop_cart::calculate_shipping_costs() );

		$data['supress_interaction'] = $supress_interaction;

		Session::set('cart_summary_prices', array(
			'summary' => $data['summary'],
			'tax' => $data['tax'],
			'shipping_cost' => $data['shipping_cost'],
			'netto' => $data['netto'],
			'brutto' => $data['brutto'],
		));

    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/shop_cart.php'))
      return View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/shop_cart.php',$data);
		else
      return View::factory('public/template/shop_cart',$data);
	}

	public static function render_simple()
	{

		$data = array();
		$data['warenkorb'] = Session::get('cart');

		$helper = new model_db_article();

		$data['summary'] = $helper->get_price(static::calculate_summary(), false, false);
		$data['article_amount'] = count($data['warenkorb']);

		$to_shop_link = array();

		if(model_generator_preparer::$isMainLanguage) {
			$to_shop_link[] = model_generator_preparer::$mainLang;
		}
		else {
			$to_shop_link[] = model_generator_preparer::$lang;
		}
		$to_shop_link[] = 'cart';
		$to_shop_link[] = 'overview';

		$data['to_cart_link'] = Uri::create(implode('/', $to_shop_link));
		$data['to_cart_label'] = __('shop.cart.to_cart');

    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/shop_cart_simple.php'))
      return View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/shop_cart_simple.php',$data);
		else
      return View::factory('public/template/shop_cart_simple',$data);
	}

}