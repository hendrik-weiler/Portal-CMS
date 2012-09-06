<?php

class model_helper_management_folder
{
	public static function look_for_missing_folders()
	{
		if(!is_dir(DOCROOT . 'uploads'))
			File::create_dir(DOCROOT , 'uploads');

		foreach (model_db_language::find('all') as $lang) 
		{
			if(!is_dir(DOCROOT . 'uploads/' . $lang->prefix))
				File::create_dir(DOCROOT . 'uploads' , $lang->prefix);

			if(!is_dir(DOCROOT . 'uploads/' . $lang->prefix . '/content'))
				File::create_dir(DOCROOT . 'uploads/' . $lang->prefix , 'content');

			if(!is_dir(DOCROOT . 'uploads/' . $lang->prefix . '/news'))
				File::create_dir(DOCROOT . 'uploads/' . $lang->prefix , 'news');

			if(!is_dir(DOCROOT . 'uploads/' . $lang->prefix . '/gallery'))
				File::create_dir(DOCROOT . 'uploads/' . $lang->prefix , 'gallery');

			if(!is_dir(DOCROOT . 'uploads/' . $lang->prefix . '/flash'))
				File::create_dir(DOCROOT . 'uploads/' . $lang->prefix , 'flash');
		}
	}
}