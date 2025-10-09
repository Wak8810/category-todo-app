<?php

namespace Model;
use Fuel\Core\DB;
class User extends \Fuel\Core\Model
{
  /**
   * メールアドレスからユーザー情報を取得
   * 
   * ログイン、重複チェックで使用
   * 
   * @param string $email メールアドレス
   * @return array|null ユーザー情報
   */
  public static function get_by_email($email)
  {
    return DB::select()->from('users')->where('email', $email)->execute()->current();
  }
  /**
   * 新規ユーザーを登録
   *
   * @param string $username ユーザー名
   * @param string $email メールアドレス
   * @param string $password 平文のパスワード
   * @return int|bool 新規作成されたユーザーID、失敗した場合はfalse
   */
  public static function create($username, $email, $password)
  {
    return \Auth::create_user(
      $username,
      $password,
      $email,
      1,
      []
    );
  }
}