<?php

namespace Fuel\Migrations;

class database
{

	public static $lang = 'dummy';

	public static $custom_lang = 'en';

	private function _createCMSDataByLang($lang)
	{
		\DBUtil::create_table($lang . '_site', array(
            'id' => array('type' => 'int', 'constraint' => 10,'auto_increment' => true),
            'navigation_id' => array('type' => 'int', 'constraint' => 10, 'null' => true),
            'group_id' => array('type' => 'int', 'constraint' => 10,'null' => true),
            'label' => array('type' => 'varchar', 'constraint' => 60, 'null' => true),
            'url_title' => array('type' => 'varchar', 'constraint' => 80, 'null' => true),
            'site_title' => array('type' => 'varchar', 'constraint' => 120, 'null' => true),
            'keywords' => array('type' => 'varchar', 'constraint' => 100, 'null' => true),
            'description' => array('type' => 'text', 'null' => true),
            'redirect' => array('type' => 'varchar', 'constraint' => 100, 'null' => true),
            'sort' => array('type' => 'int', 'constraint' => 10, 'null' => true),
            'changed' => array('type' => 'timestamp', 'default' => \DB::expr('CURRENT_TIMESTAMP')),
        ), array('id'));

		\DBUtil::create_table($lang . '_news', array(
            'id' => array('type' => 'int', 'constraint' => 10,'auto_increment' => true),
            'title' => array('type' => 'varchar', 'constraint' => 80, 'null' => true),
            'picture' => array('type' => 'text', 'null' => true),
            'text' => array('type' => 'text', 'null' => true),
            'attachment' => array('type' => 'text', 'null' => true),
            'creation_date' => array('type' => 'timestamp','default' => \DB::expr('CURRENT_TIMESTAMP'), 'null' => true),
        ), array('id'));

            \DBUtil::create_table($lang . '_navigation_group', array(
            'id' => array('type' => 'int', 'constraint' => 10,'auto_increment' => true),
            'title' => array('type' => 'varchar', 'constraint' => 80, 'null' => true),
        ), array('id'));

		\DBUtil::create_table($lang . '_navigation', array(
            'id' => array('type' => 'int', 'constraint' => 10,'auto_increment' => true),
            'group_id' => array('type' => 'int', 'constraint' => 10,'null' => true),
            'label' => array('type' => 'varchar', 'constraint' => 80, 'null' => true),
            'url_title' => array('type' => 'varchar', 'constraint' => 100, 'null' => true),
            'parent' => array('type' => 'int', 'constraint' => 11, 'null' => true),
            'sort' => array('type' => 'int', 'constraint' => 11, 'null' => true),
        ), array('id'));

		\DBUtil::create_table($lang . '_content', array(
            'id' => array('type' => 'int', 'constraint' => 10,'auto_increment' => true),
            'group_id' => array('type' => 'int', 'constraint' => 10,'null' => true),
            'site_id' => array('type' => 'int', 'constraint' => 10, 'null' => true),
            'type' => array('type' => 'int', 'constraint' => 11, 'null' => true),
            'label' => array('type' => 'varchar', 'constraint' => 60, 'null' => true),
            'text' => array('type' => 'text', 'null' => true),
            'text2' => array('type' => 'text', 'null' => true),
            'text3' => array('type' => 'text', 'null' => true),
            'parameter' => array('type' => 'text', 'null' => true),
            'wmode' => array('type' => 'text', 'null' => true),
            'flash_file' => array('type' => 'text', 'null' => true),
            'pictures' => array('type' => 'text', 'null' => true),
            'dimensions' => array('type' => 'text', 'null' => true),
            'form' => array('type' => 'text', 'null' => true),
            'refer_content_id' => array('type' => 'text', 'null' => true),
            'sort' => array('type' => 'int', 'constraint' => 11, 'null' => true),
        ), array('id'));
	}

	private function _deleteCMSDataByLang($lang)
	{
		\DBUtil::drop_table($lang . '_site');
		\DBUtil::drop_table($lang . '_content');
		\DBUtil::drop_table($lang . '_news');
		\DBUtil::drop_table($lang . '_navigation');
            \DBUtil::drop_table($lang . '_navigation_group');
	}

	public function up()
	{
		\DBUtil::create_table('languages', array(
            'id' => array('type' => 'int', 'constraint' => 10,'auto_increment' => true),
            'label' => array('type' => 'varchar', 'constraint' => 45, 'null' => true),
            'prefix' => array('type' => 'varchar', 'constraint' => 8, 'null' => true),
            'sort' => array('type' => 'int', 'constraint' => 11, 'null' => true),
        ), array('id'));

            \DBUtil::create_table('options', array(
            'id' => array('type' => 'int', 'constraint' => 10,'auto_increment' => true),
            'key' => array('type' => 'varchar', 'constraint' => 80, 'null' => true),
            'value' => array('type' => 'text', 'null' => true),
        ), array('id'));

		\DBUtil::create_table('accounts', array(
            'id' => array('type' => 'int', 'constraint' => 10,'auto_increment' => true),
            'username' => array('type' => 'varchar', 'constraint' => 15, 'null' => true),
            'password' => array('type' => 'text', 'null' => true),
            'session' => array('type' => 'varchar', 'constraint' => 100),
            'language' => array('type' => 'varchar', 'constraint' => 10, 'null' => true),
            'admin' => array('type' => 'bool', 'null' => true),
            'permissions' => array('type' => 'text', 'null' => true),
        ), array('id'));

        $this->_createCMSDataByLang(self::$lang);

        $this->_createCMSDataByLang(self::$custom_lang);
	}

	public function down()
	{
		\DBUtil::drop_table('accounts');
		\DBUtil::drop_table('languages');

		$this->_deleteCMSDataByLang(self::$lang);
		$this->_deleteCMSDataByLang(self::$custom_lang);
	}
}