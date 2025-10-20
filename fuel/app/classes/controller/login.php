<?php

use Auth\Auth;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Security;
use Fuel\Core\Session;
use Fuel\Core\View;

class Controller_Login extends \Fuel\Core\Controller
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
   * ログインフォームを表示
   */
  public function action_index()
  {
    $view = View::forge('auth/login/index');
    $view->set('form_inputs', Session::get_flash('form_inputs', []));
    return $view;
  }

  /**
   * ログイン処理
   */
  public function post_login()
  {
    if (!Security::check_token())
    {
      Session::set_flash('error', 'ページの有効期限が切れました。もう一度お試しください。');
      Response::redirect('login');
    }

    $errors = [];
    $email = Input::post('email');
    $password = Input::post('password');

    if (empty($email))
    {
      $errors['email'] = 'メールアドレスを入力してください。';
    }
    if (empty($password))
    {
      $errors['password'] = 'パスワードを入力してください。';
    }

    if (empty($errors) && Auth::login($email, $password))
    {
      Session::rotate();
      Session::set('username', Auth::get_screen_name());
      Response::redirect('/');
    }

    if (empty($errors)) {
        $errors['login'] = 'メールアドレスまたはパスワードが違います。';
    }

    Session::set_flash('errors', $errors);
    Session::set_flash('form_inputs', ['email' => $email]);
    Response::redirect('login');
  }
}