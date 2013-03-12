<?php

class Controller_Public extends Controller
{
	public function action_index()
	{
            model_generator_preparer::initialize();
            model_generator_module::checkStatus();
            $this->response->body = View::forge('public/procedural_helpers');
            $this->response->body .= model_generator_layout::render();

            $inline_edit_html = '';
		    if(model_db_option::getKey('inline_edit')->value && model_auth::check())
		    {
		      $inline_edit_html = Asset\Manager::get('js->inline_edit');
		    }

            $this->response->body = str_replace('</head>',
            	'<script>var inline_edit_language = "' . model_generator_preparer::$lang . '";</script>' 
            	. $inline_edit_html . '</head>', 
            $this->response->body);
           
	}
}

/* End of file welcome.php */