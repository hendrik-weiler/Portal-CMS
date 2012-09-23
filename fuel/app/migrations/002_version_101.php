<?php

namespace Fuel\Migrations;

class version_101 extends \Update\Migration
{

	public static $lang = 'dummy';

	private function _createCMSDataByLang($lang)
	{

        # --------------------- site ----------------------------------------

		
        # --------------------- news ----------------------------------------


        # --------------------- navigation ----------------------------------------


        # --------------------- content ----------------------------------------

        \DBUtil::add_fields($lang . '_content', array(
            'picture_order' => array('type' => 'text'),
        ));
	}

	private function _deleteCMSDataByLang($lang)
	{
		\DBUtil::drop_fields($lang . '_content', 'picture_order');
	}

	public function up()
	{

		$this->_createCMSDataByLang(self::$lang);

        foreach (static::get_languages() as $lang) 
            $this->_createCMSDataByLang($lang);
	}

	public function down()
	{
		$this->_deleteCMSDataByLang(self::$lang);

        foreach (static::get_languages() as $lang) 
            $this->_deleteCMSDataByLang($lang);
	}
}