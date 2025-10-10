<?php

use Fuel\Core\Controller;
use Fuel\Core\View;
use Fuel\Core\Response;
use Auth\Auth;

class Controller_Dashboard extends Controller
{
  public function before()
  {
    parent::before();

    // 未ログインであればログインページへリダイレクト
    if (!Auth::check())
    {
      Response::redirect('login');
    }
  }

  /**
   * ダッシュボードページ
   */
  public function action_index()
  {
    $data = array();

    // ログイン中のユーザー情報を取得
    $data['username'] = Auth::get('username');
    $data['email'] = Auth::get('email');

    return View::forge('dashboard/index', $data);
  }
}
