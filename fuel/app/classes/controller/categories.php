<?php

use Auth\Auth;
use Fuel\Core\Input;
use Model\Category;

class Controller_Categories extends \Fuel\Core\Controller_Rest
{
  /**
   * カテゴリー一覧を取得 (GET /categories)
   */
  public function get_index()
  {
    if (!Auth::check())
    {
      return $this->response(['error' => 'ログインが必要です。'], 401);
    }

    list(, $user_id) = Auth::get_user_id();
    $categories = Category::find_by_user_id($user_id);

    return $this->response($categories, 200);
  }

  /**
   * 新しいカテゴリーを作成 (POST /categories)
   */
  public function post_create()
  {
    if (!Auth::check())
    {
      return $this->response(['error' => 'ログインが必要です。'], 401);
    }

    $errors = [];
    $name = Input::json('name');
    $color_code = Input::json('color_code');
    list(, $user_id) = Auth::get_user_id();

    // カテゴリー名のバリデーション
    if (empty($name))
    {
      $errors['name'] = 'カテゴリー名は必須です。';
    }
    elseif (mb_strlen($name) > 255)
    {
      $errors['name'] = 'カテゴリー名は255文字以内で入力してください。';
    }
    elseif (Category::find_by_name_and_user_id($name, $user_id))
    {
      $errors['name'] = 'このカテゴリー名は既に使用されています。';
    }

    // カラーコードのバリデーション
    if (empty($color_code))
    {
      $errors['color_code'] = 'カラーコードは必須です。';
    }
    elseif (!preg_match('/^#[0-9a-fA-F]{6}$/', $color_code))
    {
      $errors['color_code'] = 'カラーコードの形式が正しくありません。';
    }

    if (!empty($errors))
    {
      return $this->response(['errors' => $errors], 400);
    }

    $new_id = Category::create_category($user_id, $name, $color_code);

    if ($new_id === false)
    {
      return $this->response(['error' => 'カテゴリーの作成に失敗しました。'], 500);
    }

    $new_category = Category::find_one_by_id_and_user_id($new_id, $user_id);
    return $this->response($new_category, 201);
  }

  /**
   * カテゴリーを更新 (PUT /categories/:id)
   */
  public function put_update($id = null)
  {
    if (!Auth::check())
    {
      return $this->response(['error' => 'ログインが必要です。'], 401);
    }

    list(, $user_id) = Auth::get_user_id();

    if (!Category::find_one_by_id_and_user_id($id, $user_id))
    {
      return $this->response(['error' => '指定されたカテゴリーは見つかりません。'], 404);
    }

    $errors = [];
    $name = Input::json('name');
    $color_code = Input::json('color_code');

    // カテゴリー名のバリデーション
    if (empty($name))
    {
      $errors['name'] = 'カテゴリー名は必須です。';
    }
    elseif (mb_strlen($name) > 255)
    {
      $errors['name'] = 'カテゴリー名は255文字以内で入力してください。';
    }
    else
    {
      $found = Category::find_by_name_and_user_id($name, $user_id);
      if ($found && $found['id'] != $id)
      {
        $errors['name'] = 'このカテゴリー名は既に使用されています。';
      }
    }

    // カラーコードのバリデーション
    if (empty($color_code))
    {
      $errors['color_code'] = 'カラーコードは必須です。';
    }
    elseif (!preg_match('/^#[0-9a-fA-F]{6}$/', $color_code))
    {
      $errors['color_code'] = 'カラーコードの形式が正しくありません。';
    }

    if (!empty($errors))
    {
      return $this->response(['errors' => $errors], 400);
    }

    $success = Category::update_category($id, $user_id, $name, $color_code);

    if (!$success)
    {
      return $this->response(['error' => 'カテゴリーの更新に失敗しました。'], 500);
    }

    $updated_category = Category::find_one_by_id_and_user_id($id, $user_id);
    return $this->response($updated_category, 200);
  }

  /**
   * カテゴリーを削除 (DELETE /categories/:id)
   */
  public function delete_delete($id = null)
  {
    if (!Auth::check())
    {
      return $this->response(['error' => 'ログインが必要です。'], 401);
    }

    list(, $user_id) = Auth::get_user_id();

    if (!Category::find_one_by_id_and_user_id($id, $user_id))
    {
      return $this->response(['error' => '指定されたカテゴリーは見つかりません。'], 404);
    }

    $success = Category::delete_category($id, $user_id);

    if (!$success)
    {
      return $this->response(['error' => 'カテゴリーの削除に失敗しました。'], 500);
    }

    return $this->response(null, 204);
  }
}
