<?php

namespace Model;
use Fuel\Core\DB;
use Fuel\Core\Date;

class Category extends \Fuel\Core\Model
{
  /**
   * ユーザーIDに基づいてカテゴリを取得
   * カテゴリ一覧表示に使用
   * @param int $user_id
   * @return array
   */
  public static function find_by_user_id($user_id)
  {
    return DB::select()->from('categories')
      ->where('user_id', $user_id)
      ->and_where('deleted_at', 'is', null)
      ->order_by('created_at', 'desc')
      ->execute()->as_array();
  }

  /**
   * 新しいカテゴリを作成
   * @param int $user_id
   * @param string $name
   * @param string $color_code
   * @return mixed　int|false 追加出来てたらid、失敗でfalse
   */
  public static function create_category($user_id, $name, $color_code)
  {
    $query_data = [
      'user_id' => $user_id,
      'name' => $name,
      'color_code' => $color_code,
      'created_at' => Date::forge()->format('mysql'),
      'updated_at' => Date::forge()->format('mysql'),
    ];

    list($insert_id, $rows_affected) = DB::insert('categories')->set($query_data)->execute();

    return ($rows_affected > 0) ? $insert_id : false;
  }

  /**
   * カテゴリの更新
   * @param int $id
   * @param int $user_id
   * @param string $name
   * @param string $color_code
   * @return bool
   */
  public static function update_category($id, $user_id, $name, $color_code)
  {
    $query_data = [
      'name' => $name,
      'color_code' => $color_code,
      'updated_at' => Date::forge()->format('mysql'),
    ];

    $result = DB::update('categories')->set($query_data)
      ->where('id', $id)
      ->and_where('user_id', $user_id)
      ->execute();

    return ($result > 0);
  }

  /**
   * カテゴリを論理削除する
   * @param int $id
   * @param int $user_id
   * @return bool
   */
  public static function delete_category($id, $user_id)
  {
    $query_data = array('deleted_at' => Date::forge()->format('mysql'));

    $result = DB::update('categories')->set($query_data)
      ->where('id', $id)
      ->and_where('user_id', $user_id)
      ->execute();

    return ($result > 0);
  }

  /**
   * IDとユーザーIDでカテゴリを1件取得する
   * 編集等での確認で使用
   * @param int $id
   * @param int $user_id
   * @return mixed
   */
  public static function find_one_by_id_and_user_id($id, $user_id)
  {
    return DB::select()->from('categories')
      ->where('id', $id)
      ->and_where('user_id', $user_id)
      ->and_where('deleted_at', 'is', null)
      ->execute()->current();
  }

  /**
   * 名前とユーザーIDでカテゴリを1件取得する
   * nameの重複チェック用
   * @param string $name
   * @param int $user_id
   * @return mixed
   */
  public static function find_by_name_and_user_id($name, $user_id)
  {
    return DB::select()->from('categories')
      ->where('name', $name)
      ->and_where('user_id', $user_id)
      ->and_where('deleted_at', 'is', null)
      ->execute()->current();
  }
}
