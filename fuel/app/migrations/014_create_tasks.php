<?php

namespace Fuel\Migrations;
use Fuel\Core\DBUtil;

class Create_tasks
{
  public function up()
  {
    DBUtil::create_table('tasks', array(
      'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
      'user_id' => array('constraint' => 11, 'type' => 'int'),
      'category_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
      'title' => array('constraint' => 255, 'type' => 'varchar'),
      'is_completed' => array('type' => 'bool', 'default' => 0),
      'created_at' => array('type' => 'datetime', 'null' => false),
      'updated_at' => array('type' => 'datetime', 'null' => false),
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
        array(
          'key' => 'category_id',
          'reference' => array(
            'table' => 'categories',
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
    DBUtil::drop_table('tasks');
  }
}
