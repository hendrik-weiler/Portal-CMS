<?php

namespace Fuel\Migrations;

class version_1038 extends \Update\Migration
{

	public static $lang = 'dummy';

	public function up()
	{
			# --------------------- article ----------------------------------------

			\DBUtil::create_table('article', array(
					'id' => array('type' => 'int', 'constraint' => 10,'auto_increment' => true),
					'nr' => array('type' => 'varchar', 'constraint' => 80, 'null' => true),
					'label' => array('type' => 'text', 'null' => true),
					'price' => array('type' => 'float', 'constraint' => 11, 'null' => true),
					'article_group_id' => array('type' => 'int', 'constraint' => 11, 'null' => true),
					'tax_group_id' => array('type' => 'int', 'constraint' => 11, 'null' => true),
					'description' => array('type' => 'text', 'null' => true),
					'images' => array('type' => 'text', 'null' => true),
					'main_image_index' => array('type' => 'int', 'constraint' => 10,'default' => 0),
					'sold_out' => array('type' => 'int', 'constraint' => 1),
			), array('id'), false, 'InnoDB');

			# --------------------- article group ----------------------------------------

			\DBUtil::create_table('article_group', array(
					'id' => array('type' => 'int', 'constraint' => 10,'auto_increment' => true),
					'label' => array('type' => 'text', 'null' => true),
			), array('id'), false, 'InnoDB');

			# --------------------- tax group ----------------------------------------

			\DBUtil::create_table('tax_group', array(
					'id' => array('type' => 'int', 'constraint' => 10,'auto_increment' => true),
					'label' => array('type' => 'text', 'null' => true),
					'value' => array('type' => 'float', 'constraint' => 11, 'null' => true),
			), array('id'), false, 'InnoDB');

			# --------------------- orders ----------------------------------------

			\DBUtil::create_table('order', array(
					'id' => array('type' => 'int', 'constraint' => 10,'auto_increment' => true),
					'delivery_address' => array('type' => 'text', 'null' => true),
					'cart' => array('type' => 'text', 'null' => true),
					'summary_prices' => array('type' => 'text', 'null' => true),
					'accept' => array('type' => 'int', 'constraint' => 1),
					'canceled' => array('type' => 'int', 'constraint' => 1),
					'created_at' => array('type' => 'timestamp', 'default' => \DB::expr('CURRENT_TIMESTAMP')),
			), array('id'), false, 'InnoDB');

			$tax_group = new \model_db_tax_group();
			$tax_group->label = 'Standard';
			$tax_group->value = 19;
			$tax_group->save();

			$tax_group = new \model_db_tax_group();
			$tax_group->label = 'Books';
			$tax_group->value = 7;
			$tax_group->save();
		
	}

	public function down()
	{
		\DBUtil::drop_table('article');
		\DBUtil::drop_table('article_group');
		\DBUtil::drop_table('tax_group');
	}
}