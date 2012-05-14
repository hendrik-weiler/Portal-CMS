<?php

namespace test;

class rest implements \plugin
{	
	public function get_options()
	{
		return array(
			'form' => array(
				'rest' => 'textbox',
				'rest2' => 'textarea',
				'rest3' => array(
					'p1' => 'Point 1',
					'p2' => 'Point 2',
					'p3' => 'Point 3'
				)
			),
			'default' => array(
				'rest' 	  => 'default1',
				'rest2'   => 'default1',
				'rest3'   => 'p2'
			),
		);
	}

	public function render()
	{
		return "this is a rest!";
	}
}