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
  /**
   * ログインフォームを表示
   */
  public function action_index()
  {
    if (Auth::check())
    {
      Response::redirect('/');
    }
    return View::forge('login/index');
  }

  /**
   * ログイン処理
   */
  public function action_login()
  {
    if (Auth::check())
    {
      Response::redirect('/');
    }

    if (Input::method() !== 'POST')
    {
      Response::redirect('login');
    }

    if (!Security::check_token())
    {
      $view = View::forge('login/index');
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
        
        Response::redirect('/');
      }
      else
      {
        $errors['login'] = 'メールアドレスまたはパスワードが違います。';
      }
    }

    $view = View::forge('login/index');
    $view->set('errors', $errors);
    return $view;
  }
}