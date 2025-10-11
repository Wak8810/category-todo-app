<?php

use Fuel\Core\Controller;
use Fuel\Core\Response;
use Fuel\Core\Session;
use Auth\Auth;

class Controller_Logout extends Controller
{
  /**
   * GET /logout
   * 誤ってGETでアクセスされた場合はトップページにリダイレクト（不要かも）
   */
  public function get_index()
  {
    Response::redirect('/');
  }

  /**
   * POST /logout
   * ログアウト処理を実行
   */
  public function post_index()
  {
    // ログインしている場合のみログアウト処理を実行
    if (Auth::check())
    {
      Auth::logout();
      Session::delete('username');
    }
    
    Response::redirect('login');
  }
}
