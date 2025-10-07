<?php

namespace Model;
class User extends \Fuel\Core\Model
{
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