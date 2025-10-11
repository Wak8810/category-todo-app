<?php

namespace Fuel\Migrations;

class Create_categories
{
  public function up()
  {
    \DBUtil::create_table('categories', array(
      'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
      'name' => array('constraint' => 255, 'type' => 'varchar'),
      'user_id' => array('constraint' => 11, 'type' => 'int'),
      'color_code' => array('constraint' => 7, 'type' => 'char', 'default' => '#000000'),
      'created_at' => array('type' => 'datetime', 'null' => true),
      'updated_at' => array('type' => 'datetime', 'null' => true),
      'deleted_at' => array('type' => 'datetime', 'null' => true),
    ), array('id'), true, 'InnoDB', 'utf8',
      array(
        array(
          'key' => 'user_id',
          'reference' => array(
            'table' => 'users',
            'column' => 'id',
          ),
          'on_update' => 'CASCADE',
          'on_delete' => 'CASCADE',
        ),
      )
    );
  }

  public function down()
  {
    \DBUtil::drop_table('categories');
  }
}
