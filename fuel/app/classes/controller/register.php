<?php

use Fuel\Core\Session;
use Fuel\Core\Security;
use Fuel\Core\Response;
use Fuel\Core\Input;
use Fuel\Core\View;
use Model\User;

class Controller_Register extends \Fuel\Core\Controller
{
  /**
   * ユーザー登録フォームを表示
   */
  public function action_index()
  {
    if (Session::get('user_id'))
    {
      Response::redirect('/');
    }
    return View::forge('register/index');
  }

  /**
   * ユーザー登録処理
   */
  public function action_register()
  {
    if (Session::get('user_id'))
    {
      Response::redirect('/');
    }

    if (Input::method() !== 'POST')
    {
      Response::redirect('register');
    }

    if (!Security::check_token())
    {
      $view = View::forge('register/index');
      $view->set('error', 'ページの有効期限が切れました。もう一度やり直してください。');
      return $view;
    }

    $errors = array();
    $username = Input::post('username');
    $email = Input::post('email');
    $password = Input::post('password');
    $password_confirm = Input::post('password_confirm');

    // ユーザー名のバリデーション
    if (empty($username))
    {
      $errors['username'] = 'ユーザー名は必須です。';
    }
    elseif (mb_strlen($username) < 3)
    {
      $errors['username'] = 'ユーザー名は3文字以上で入力してください。';
    }
    elseif (mb_strlen($username) > 30)
    {
      $errors['username'] = 'ユーザー名は30文字以下で入力してください。';
    }
    elseif (User::get_by_username(Input::post('username')))
    {
      $errors['username'] = '存在しているユーザーネームです。';
    }

    // メールアドレスのバリデーション
    if (empty($email))
    {
      $errors['email'] = 'メールアドレスは必須です。';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
      $errors['email'] = '有効なメールアドレスを入力してください。';
    }
    elseif (User::get_by_email($email))
    {
      $errors['email'] = 'このメールアドレスは既に使用されています。';
    }

    // パスワードのバリデーション
    if (empty($password))
    {
      $errors['password'] = 'パスワードは必須です。';
    }
    elseif (mb_strlen($password) < 8)
    {
      $errors['password'] = 'パスワードは8文字以上で入力してください。';
    }

    // パスワード（確認）のバリデーション
    if ($password !== $password_confirm)
    {
      $errors['password_confirm'] = 'パスワードが一致しません。';
    }

    // エラー配列が空かどうかで処理を分岐
    if (empty($errors))
    {
      // バリデーション成功：ユーザー作成処理
      $user_id = User::create($username, $email, $password);

      if ($user_id)
      {
        Session::set_flash('success', 'ユーザー登録完了');
        Response::redirect('login');
        return;
      }
      else //念のため
      {
        $view = View::forge('register/index');
        $view->set('error', '予期せぬエラーで登録に失敗しました。');
        return $view;
      }
    }
    else
    {
      // バリデーション失敗：エラーと入力値をビューに渡す
      $view = View::forge('register/index');
      $view->set('errors', $errors);
      return $view;
    }
  }
}