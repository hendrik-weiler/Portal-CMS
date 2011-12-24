<?php

class Controller_Public extends Controller
{
	public function action_index()
	{
    model_generator_preparer::initialize();
    model_generator_module::checkStatus();
    $this->response->body = View::forge('public/procedural_helpers');
		$this->response->body .= model_generator_layout::render();
	}
}

/* End of file welcome.php */