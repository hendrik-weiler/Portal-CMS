<?php

class Controller_Public extends Controller
{
	public function action_index()
	{
    model_generator_preparer::initialize();
		$this->response->body = View::factory('public/index');
	}

  public function action_404()
  {
    $this->response->status = 404;
    $this->response->body = View::factory('public/404');
  }
}

/* End of file welcome.php */