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
class model_db_article extends Orm\Model
{

	protected static $_table_name = 'article';

	protected static $_properties = array('id', 'nr', 'label', 'price','article_group_id','tax_group_id','description','images', 'main_image_index', 'sold_out');

	public function get_label($lang_prefix='') 
	{
		$labels = Format::forge($this->label,'json')->to_array();

		if(model_generator_preparer::$isMainLanguage) {
			$lang_prefix = model_db_language::find('first',array(
				'where' => array('sort'=>0)
			))->prefix;
		}

		if(!isset($labels[$lang_prefix]) or $labels[$lang_prefix] == '') {
			$lang_prefix = model_generator_preparer::$lang;
		}

		return $labels[$lang_prefix];
	}

	public function get_description($lang_prefix='') 
	{
		$labels = Format::forge($this->description,'json')->to_array();

		if(model_generator_preparer::$isMainLanguage) {
			$lang_prefix = model_db_language::find('first',array(
				'where' => array('sort'=>0)
			))->prefix;
		}

		if(!isset($labels[$lang_prefix]) or $labels[$lang_prefix] == '') {
			$lang_prefix = model_generator_preparer::$lang;
		}

		return $labels[$lang_prefix];
	}

	public function get_label_group() 
	{
		return Format::forge($this->label,'json')->to_array();
	}

	public function get_main_image($type = 'thumbs')
	{
		$image = Format::forge($this->images,'json')->to_array();

		if(isset($image[$this->main_image_index])) {
			$url = Uri::create('uploads/shop/article/' . $this->id . '/' . $type . '/' . $image[$this->main_image_index]);
		}
		else {
			$url = Uri::create('');
		}

		return $url;
	}

	public function get_price($price = 0, $raw = false, $rate_change = true, $currency_definition = array('de'=>' €','en'=>' $'))
	{
		$lang_prefix = model_generator_preparer::$lang;

		if(model_generator_preparer::$isMainLanguage) {
			$lang_prefix = model_db_language::find('first',array(
				'where' => array('sort'=>0)
			))->prefix;
		}

		if($price == 0) $price = $this->price;

		if($rate_change) {
			$rates = model_db_option::getKey('exchange_rates')->value;
			$rates = Format::forge($rates,'json')->to_array();
		}

		!isset($rates[$lang_prefix]) and $rates[$lang_prefix] = 1;

		$price = $price / floatval($rates[$lang_prefix]);

		if(!$raw) {
			$price = number_format($price, 2, ',', '') . $currency_definition[$lang_prefix];
		}

		return $price;

	}

	public function get_price_by_amount($amount)
	{
		return $this->get_price($this->get_price(0,true) * $amount, false, false);
	}

	public function get_fullview_link()
	{
		$uri = array();
		if(!empty(model_generator_preparer::$lang))
			$uri[] = model_generator_preparer::$lang;

		if(!empty(model_generator_preparer::$main))
			$uri[] = model_generator_preparer::$main;

		if(!empty(model_generator_preparer::$sub))
			$uri[] = model_generator_preparer::$sub;

		$uri[] = 'product';
		$uri[] = $this->id;
		$uri[] = model_generator_seo::friendly_title($this->get_label(model_generator_preparer::$lang));

		return Uri::create(implode('/', $uri));
	}

	public function get_netto_price($rate_change = true)
	{
		$tax_group = model_db_tax_group::find($this->tax_group_id);

		$lang_prefix = model_generator_preparer::$lang;

		$price = $this->price;

		if(model_generator_preparer::$isMainLanguage) {
			$lang_prefix = model_db_language::find('first',array(
				'where' => array('sort'=>0)
			))->prefix;
		}

		$rates = array();

		if($rate_change) {
			$rates = model_db_option::getKey('exchange_rates')->value;
			$rates = Format::forge($rates,'json')->to_array();
		}

		!isset($rates[$lang_prefix]) and $rates[$lang_prefix] = 1;

		$price = $price / floatval($rates[$lang_prefix]);

		return $price / ((100 + $tax_group->value)/100);
	}

	public function remove_from_cart_anchor($index)
	{
		$lang = model_generator_preparer::$lang;
		return '<a href="' . Uri::create($lang . '/cart/remove/' . $index . '?return=' . Uri::current()) . '">' . __('shop.cart.remove_from_cart') . '</a>';
	}

}