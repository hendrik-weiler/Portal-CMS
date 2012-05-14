<?php

namespace test;

class html implements \plugin
{
	public function get_options()
	{
		return array(
			'form' => array(
				'test' => 'textbox',
				'test2' => 'textarea',
				'test3' => array(
					'p1' => 'Point 1',
					'p2' => 'Point 2',
					'p3' => 'Point 3'
				),
				'is_cool' => 'checkbox'
			),
			'default' => array(
				'test' 	  => 'default1',
				'test2'   => 'default1',
				'test3'   => 'p2',
				'is_cool' => '1',
			),
		);
	}

	public function render()
	{
		return "this is a test!";
	}
}