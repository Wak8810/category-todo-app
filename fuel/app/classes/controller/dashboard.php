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
    return View::forge('dashboard/index');
  }
}
