<?php

use Auth\Auth;
use Fuel\Core\Security;
use Model\Task;

class Controller_Api_Tasks extends \Fuel\Core\Controller_Rest
{
  public $user_id;

  public function before()
  {
    parent::before();

    if (!Auth::check())
    {
      return $this->response(['status' => 'error', 'message' => '認証が必要です。'], 401);
    }
    list(, $this->user_id) = Auth::get_user_id();
  }

  /**
   * タスクを切り替えるAPI
   */
  public function post_toggle($id = null)
  {
    if (!Security::check_token())
    {
      return $this->response(['status' => 'error', 'message' => '不正なリクエストです。ページを再読み込みしてもう一度お試しください。'], 403);
    }

    try {
      if (!$id)
      {
        return $this->response(['status' => 'error', 'message' => 'タスクIDが必要です。'], 400);
      }

      $task = Task::find_one_by_id_and_user_id($id, $this->user_id);
      if (!$task)
      {
        return $this->response(['status' => 'error', 'message' => 'タスクが見つからないか、権限がありません。'], 404);
      }

      if (Task::toggle_completed($id, $this->user_id))
      {
        return $this->response([
          'status' => 'ok', 
          'message' => 'データベースの更新は成功しました。',
          'new_csrf_token' => Security::fetch_token(),
        ], 200);
      }
      else
      {
        return $this->response(['status' => 'error', 'message' => 'データベースの更新は成功しましたが、影響行数が0と報告されました。'], 500);
      }
    } catch (\Exception $e) {
      return $this->response([
        'status' => 'error',
        'message' => 'サーバーで予期せぬエラーが発生しました。',
        'error_details' => $e->getMessage()
      ], 500);
    }
  }
}