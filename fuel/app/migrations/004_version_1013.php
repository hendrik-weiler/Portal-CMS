<?php

namespace Fuel\Migrations;

class version_1013 extends \Update\Migration
{

	public static $lang = 'dummy';

	private function _createCMSDataByLang($lang)
	{

        # --------------------- site ----------------------------------------

		
        # --------------------- news ----------------------------------------


        # --------------------- navigation ----------------------------------------


        # --------------------- content ----------------------------------------

        \DBUtil::add_fields($lang . '_navigation', array(
            'image' => array('type' => 'text','null' => true),
            'image_is_shown' => array('type' => 'int', 'constraint' => 1,'default' => 1,'null' => true),
        ));
	}

	private function _deleteCMSDataByLang($lang)
	{
		\DBUtil::drop_fields($lang . '_navigation', 'image');
		\DBUtil::drop_fields($lang . '_navigation', 'image_is_shown');
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