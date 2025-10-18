<?php

namespace Model;

use Fuel\Core\DB;
use Fuel\Core\Date;

class Task extends \Fuel\Core\Model
{
  /**
   * ユーザーIDに基づいてタスクを取得
   * カテゴリーによる絞り込みも可能
   * カテゴリーテーブルと結合し、カテゴリーの色情報も取得(N+1問題の対策)
   * 一覧表示に使用
   *
   * @param int $user_id ユーザーID
   * @param int|null $category_id カテゴリーID (任意)
   * @return array タスクの配列
   */
  public static function find_by_user_id($user_id, $category_id = null)
  {
    $query = DB::select(
      'tasks.*',
      ['categories.name', 'category_name'],
      ['categories.color_code', 'category_color_code']
    )
      ->from('tasks')
      ->join('categories', 'LEFT')->on('tasks.category_id', '=', 'categories.id')
      ->where('tasks.user_id', $user_id)
      ->and_where('tasks.deleted_at', 'is', null);

    if ($category_id !== null) {
      $query->and_where('tasks.category_id', $category_id);
    }

    return $query->order_by('created_at', 'desc')->execute()->as_array();
  }

  /**
   * IDとユーザーIDでタスクを1件取得
   * 編集等の時に使用
   *
   * @param int $id タスクID
   * @param int $user_id ユーザーID
   * @return mixed タスク情報 or false
   */
  public static function find_one_by_id_and_user_id($id, $user_id)
  {
    return DB::select()
      ->from('tasks')
      ->where('id', $id)
      ->and_where('user_id', $user_id)
      ->and_where('deleted_at', 'is', null)
      ->execute()->current();
  }

  /**
   * 新しいタスクを作成
   *
   * @param array $data タスクのデータ
   * @return mixed 作成されたタスクのID or false
   */
  public static function create_task($data)
  {
    $query_data = [
      'user_id' => $data['user_id'],
      'category_id' => $data['category_id'],
      'title' => $data['title'],
      'created_at' => Date::forge()->format('mysql'),
      'updated_at' => Date::forge()->format('mysql'),
    ];

    list($insert_id, $rows_affected) = DB::insert('tasks')->set($query_data)->execute();

    return ($rows_affected > 0) ? $insert_id : false;
  }

  /**
   * タスクの名前、カテゴリーを更新
   *
   * @param int $id タスクID
   * @param int $user_id ユーザーID
   * @param array $data 更新データ
   * @return bool 成功したかどうか
   */
  public static function update_task($id, $user_id, $data)
  {
    $query_data = [
      'title' => $data['title'],
      'category_id' => $data['category_id'],
      'updated_at' => Date::forge()->format('mysql'),
    ];

    $result = DB::update('tasks')->set($query_data)
      ->where('id', $id)
      ->and_where('user_id', $user_id)
      ->execute();

    return ($result > 0);
  }

  /**
   * タスクの完了状態の切り替え
   *
   * @param int $id タスクID
   * @param int $user_id ユーザーID
   * @return bool 成功したかどうか
   */
  public static function toggle_completed($id, $user_id)
  {
    $result = DB::update('tasks')
      ->set([
        'is_completed' => DB::expr('NOT is_completed'), //反転して代入
        'updated_at' => Date::forge()->format('mysql'),
      ])
      ->where('id', $id)
      ->and_where('user_id', $user_id)
      ->execute();

    return ($result > 0);
  }

  /**
   * タスクの論理削除
   *
   * @param int $id タスクID
   * @param int $user_id ユーザーID
   * @return bool 成功したかどうか
   */
  public static function delete_task($id, $user_id)
  {
    $query_data = [
      'deleted_at' => Date::forge()->format('mysql')
    ];

    $result = DB::update('tasks')->set($query_data)
      ->where('id', $id)
      ->and_where('user_id', $user_id)
      ->execute();

    return ($result > 0);
  }
}
