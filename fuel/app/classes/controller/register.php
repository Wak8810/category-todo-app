<?php

use Auth\Auth;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Security;
use Fuel\Core\Session;
use Fuel\Core\View;
use Model\User;

class Controller_Register extends \Fuel\Core\Controller
{
  public function before()
  {
    parent::before();
    
    //ログイン済みならルートページに
    if (Auth::check())
    {
      Response::redirect('/');
    }
  }

  /**
   * ユーザー登録フォームを表示
   */
  public function action_index()
  {
    $view = View::forge('auth/register/index');
    $view->set('form_inputs', Session::get_flash('form_inputs', []));
    return $view;
  }

  /**
   * ユーザー登録処理
   */
  public function post_register()
  {
    if (!Security::check_token())
    {
      Session::set_flash('error', 'ページの有効期限が切れました。もう一度やり直してください。');
      Response::redirect('register');
    }

    $errors = [];
    $username = Input::post('username');
    $email = Input::post('email');
    $password = Input::post('password');
    $password_confirm = Input::post('password_confirm');

    // ユーザー名のバリデーション
    if (empty($username))
    {
      $errors['username'] = 'ユーザー名は必須です。';
    }
    elseif (!preg_match('/^[\x{3041}-\x{3093}\x{30A1}-\x{30F6}\x{4E00}-\x{9FA5}a-zA-Z0-9_-\x{30FC}]+$/u', $username))
    {
      $errors['username'] = 'ユーザー名に使用できない文字が含まれています。日本語、英数字、ハイフン、アンダースコアのみ使用できます。';
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
        
      }
      else //念のため
      {
        Session::set_flash('error', '予期せぬエラーで登録に失敗しました。');
        Response::redirect('register');
      }
    }
    else
    {
      // バリデーション失敗：エラーと入力値をフラッシュセッションに保存してリダイレクト
      Session::set_flash('errors', $errors);
      Session::set_flash('form_inputs', Input::post());
      Response::redirect('register');
    }
  }
}