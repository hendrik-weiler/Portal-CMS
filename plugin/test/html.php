<?php

namespace projects;

class view implements \plugin
{
	public function get_options()
	{
		return array(
			'form' => array(
			),
			'default' => array(
			),
		);
	}

	public function render()
	{
		return "this is a test!";
	}
}