<?php

class Controller_Public extends Controller
{
	public function action_index()
	{
    model_generator_preparer::initialize();
		$this->response->body = View::factory('public/index');
	}
}

/* End of file welcome.php */