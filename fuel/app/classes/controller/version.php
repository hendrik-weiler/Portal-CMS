<?php

class Controller_Version extends Controller
{
	public function action_update()
	{
    model_auth::check_startup();
		$_lang = $this->param('lang');
		$_lang = model_db_language::find($_lang);
		Session::set('lang_prefix',$_lang->prefix);
	}
}