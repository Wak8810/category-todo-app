<?php

use Auth\Auth;
use Fuel\Core\Input;
use Fuel\Core\Controller;
use Fuel\Core\Response;
use Fuel\Core\View;
use Fuel\Core\Session;
use Model\Category;

class Controller_Categories extends Controller
{
  public $user_id;

  public function before()
  {
    parent::before();
    
    if (!Auth::check())
    {
      Response::redirect('login');
    }
    list(, $this->user_id) = Auth::get_user_id();
  }

  /**
   * カテゴリー一覧を表示
   */
  public function action_index()
  {
    $categories = Category::find_by_user_id($this->user_id);
    $view = View::forge('category/index');
    $view->set('categories', $categories);
    $view->set('form_inputs', Session::get_flash('form_inputs', []));
    return $view;
  }

  /**
   * 編集ページを表示
   */
  public function action_edit($id = null)
  {
    $category = Category::find_one_by_id_and_user_id($id, $this->user_id);

    if (!$category) {
      Session::set_flash('error', '指定されたカテゴリーは見つかりません。');
      Response::redirect('categories');
    }

    $view = View::forge('category/edit');
    $view->set('category', $category);
    $view->set('form_inputs', Session::get_flash('form_inputs', []));
    return $view;
  }

  /**
   * 新しいカテゴリーを作成
   */
  public function post_create()
  {
    $errors = [];
    $name = Input::post('name');
    $color_code = Input::post('color_code');

    if (empty($name))
    {
      $errors['name'] = 'カテゴリー名は必須です。';
    }
    elseif (!preg_match('/^[\x{3041}-\x{3093}\x{30A1}-\x{30F6}\x{4E00}-\x{9FA5}a-zA-Z0-9_-\x{30FC}]+$/u', $name))
    {
      $errors['name'] = 'カテゴリー名に使用できない文字が含まれています。日本語、英数字、ハイフン、アンダースコアのみ使用できます。';
    }
    elseif (mb_strlen($name) > 255)
    {
      $errors['name'] = 'カテゴリー名は255文字以内で入力してください。';
    }
    elseif (Category::find_by_name_and_user_id($name, $this->user_id))
    {
      $errors['name'] = 'このカテゴリー名は既に使用されています。';
    }

    if (empty($color_code))
    {
      $errors['color_code'] = 'カラーコードは必須です。';
    }
    elseif (!preg_match('/^#[0-9a-fA-F]{6}$/', $color_code))
    {
      $errors['color_code'] = 'カラーコードの形式が正しくありません。';
    }

    if (empty($errors))
    {
      $new_id = Category::create_category($this->user_id, $name, $color_code);
      if ($new_id !== false)
      {
        Session::set_flash('success', 'カテゴリーの作成に成功しました。');
        Response::redirect('categories');
      }
      else
      {
        $errors['create'] = '何かのエラーが発生しました。';
      }
    }
    
    Session::set_flash('errors', $errors);
    Session::set_flash('form_inputs', Input::post());
    Response::redirect('categories');
  }

  /**
   * カテゴリーを更新
   */
  public function post_update($id = null)
  {
    $category = Category::find_one_by_id_and_user_id($id, $this->user_id);

    if (!$category)
    {
      Session::set_flash('error', '指定されたカテゴリーは見つかりません。');
      Response::redirect('categories');
    }

    $errors = [];
    $name = Input::post('name');
    $color_code = Input::post('color_code');

    if (empty($name))
    {
      $errors['name'] = 'カテゴリー名は必須です。';
    }
    elseif (!preg_match('/^[\x{3041}-\x{3093}\x{30A1}-\x{30F6}\x{4E00}-\x{9FA5}a-zA-Z0-9_-\x{30FC}]+$/u', $name))
    {
      $errors['name'] = 'カテゴリー名に使用できない文字が含まれています。日本語、英数字、ハイフン、アンダースコアのみ使用できます。';
    }
    elseif (mb_strlen($name) > 255)
    {
      $errors['name'] = 'カテゴリー名は255文字以内で入力してください。';
    }
    else
    {
      $found = Category::find_by_name_and_user_id($name, $this->user_id);
      if ($found && $found['id'] != $id)
      {
        $errors['name'] = 'このカテゴリー名は既に使用されています。';
      }
    }

    if (empty($color_code))
    {
      $errors['color_code'] = 'カラーコードは必須です。';
    }
    elseif (!preg_match('/^#[0-9a-fA-F]{6}$/', $color_code))
    {
      $errors['color_code'] = 'カラーコードの形式が正しくありません。';
    }

    if (empty($errors))
    {
      $success = Category::update_category($id, $this->user_id, $name, $color_code);
      if ($success)
      {
        Session::set_flash('success', 'カテゴリーを更新しました。');
        Response::redirect('categories');
      }
      else
      {
        $errors['update'] = 'カテゴリーの更新に失敗しました。何らかのエラーが発生しました。';
      }
    }

    Session::set_flash('errors', $errors);
    Session::set_flash('form_inputs', Input::post());
    Response::redirect('categories/edit/' . $id);
  }

  /**
   * カテゴリーを削除
   */
  public function post_delete($id = null)
  {
    if (!Category::find_one_by_id_and_user_id($id, $this->user_id))
    {
      Session::set_flash('error', '指定されたカテゴリーは見つかりません。');
    }
    else
    {
      $success = Category::delete_category($id, $this->user_id);
      if ($success)
      {
        Session::set_flash('success', 'カテゴリーを削除しました。');
      }
      else
      {
        Session::set_flash('error', 'カテゴリーの削除に失敗しました。');
      }
    }

    Response::redirect('categories');
  }
}
