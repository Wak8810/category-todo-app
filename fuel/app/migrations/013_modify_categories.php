<?php

namespace Fuel\Migrations;
use Fuel\Core\DBUtil;

class Modify_categories
{
	public function up()
	{
		DBUtil::modify_fields('categories', array(
			'created_at' => array('type' => 'datetime', 'null' => false),
			'updated_at' => array('type' => 'datetime', 'null' => false),
		));
	}

	public function down()
	{
		DBUtil::modify_fields('categories', array(
			'created_at' => array('type' => 'datetime', 'null' => true),
			'updated_at' => array('type' => 'datetime', 'null' => true),
		));
	}
}
