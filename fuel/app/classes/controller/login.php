<?php

use Fuel\Core\Controller;
use Fuel\Core\View;
use Fuel\Core\Response;
use Fuel\Core\Input;
use Fuel\Core\Security;
use Fuel\Core\Session;
use Auth\Auth;

class Controller_Login extends Controller
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
    return View::forge('auth/login/index');
  }

  /**
   * ログイン処理
   */
  public function post_login()
  {
    if (!Security::check_token())
    {
      $view = View::forge('auth/login/index');
      $view->set('error', 'ページの有効期限が切れました。もう一度お試しください。');
      return $view;
    }

    $errors = array();
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

    if (empty($errors))
    {
      if (Auth::login($email, $password))
      {
        //念のため
        Session::rotate();
        Session::set('username', Auth::get_screen_name());
        
        Response::redirect('/');
      }
      else
      {
        $errors['login'] = 'メールアドレスまたはパスワードが違います。';
      }
    }

    $view = View::forge('auth/login/index');
    $view->set('errors', $errors);
    return $view;
  }
}