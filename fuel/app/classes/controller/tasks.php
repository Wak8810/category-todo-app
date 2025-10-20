<?php

use Auth\Auth;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Security;
use Fuel\Core\Session;
use Fuel\Core\View;
use Model\Task;
use Model\Category;

class Controller_Tasks extends \Fuel\Core\Controller
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
   * タスク一覧（Todo/Done）を表示
   */
  public function action_index()
  {
    $tasks = Task::find_by_user_id($this->user_id);
    $categories = Category::find_by_user_id($this->user_id);

    if (empty($categories))
    {
      Session::set_flash('success', 'まずはカテゴリーを作成しましょう。カテゴリーを作成後に登録できます。');
    }

    $grouped_tasks = [
      'todo' => [],
      'done' => [],
    ];
    foreach ($tasks as $task) {
      if ($task['is_completed']) {
        $grouped_tasks['done'][] = $task;
      } else {
        $grouped_tasks['todo'][] = $task;
      }
    }

    $view = View::forge('task/index');
    $view->set('todo_tasks', $grouped_tasks['todo']);
    $view->set('done_tasks', $grouped_tasks['done']);
    $view->set('categories', $categories);
    $view->set('form_inputs', Session::get_flash('form_inputs', []));
    
    return $view;
  }

  /**
   * 特定のタスクの編集ページを表示
   */
  public function action_edit($id = null)
  {
    $task = Task::find_one_by_id_and_user_id($id, $this->user_id);

    if (!$task)
    {
      Session::set_flash('error', '指定されたタスクは見つかりません。');
      Response::redirect('tasks');
    }

    $categories = Category::find_by_user_id($this->user_id);

    $view = View::forge('task/edit');
    $view->set('task', $task);
    $view->set('categories', $categories);
    $view->set('form_inputs', Session::get_flash('form_inputs', []));
    
    return $view;
  }

  /**
   * 新しいタスクを作成
   */
  public function post_create()
  {
    if (!Security::check_token())
    {
      Session::set_flash('error', 'ページの有効期限が切れました。もう一度やり直してください。');
      Response::redirect('tasks');
    }

    $errors = [];
    $title = Input::post('title');
    $category_id = Input::post('category_id');

    if (empty($title))
    {
      $errors['title'] = 'タスク名は必須です。';
    }
    elseif (mb_strlen($title) > 255)
    {
      $errors['title'] = 'タスク名は255文字以内で入力してください。';
    }

    if (empty($category_id))
    {
      $errors['category_id'] = 'カテゴリーは必須です。';
    }
    elseif (!Category::find_one_by_id_and_user_id($category_id, $this->user_id))
    {
      $errors['category_id'] = '指定されたカテゴリーは存在しません。';
    }

    if (empty($errors))
    {
      $data = [
        'user_id' => $this->user_id,
        'title' => e($title),
        'category_id' => $category_id,
      ];
      $new_id = Task::create_task($data);

      if ($new_id !== false)
      {
        Session::set_flash('success', 'タスクを作成しました。');
      }
      else
      {
        Session::set_flash('error', 'タスクの作成に失敗しました。');
      }
    }
    else
    {
      Session::set_flash('errors', $errors);
      Session::set_flash('form_inputs', Input::post());
    }
    
    Response::redirect('tasks');
  }

  /**
   * 既存のタスクを更新
   */
  public function post_update($id = null)
  {
    if (!Security::check_token())
    {
      Session::set_flash('error', 'ページの有効期限が切れました。もう一度やり直してください。');
      Response::redirect('tasks');
    }

    $task = Task::find_one_by_id_and_user_id($id, $this->user_id);
    if (!$task)
    {
      Session::set_flash('error', '指定されたタスクは見つかりません。');
      Response::redirect('tasks');
    }

    $errors = [];
    $title = Input::post('title');
    $category_id = Input::post('category_id');

    if (empty($title))
    {
      $errors['title'] = 'タスク名は必須です。';
    }
    elseif (mb_strlen($title) > 255)
    {
      $errors['title'] = 'タスク名は255文字以内で入力してください。';
    }

    if (empty($category_id))
    {
      $errors['category_id'] = 'カテゴリーは必須です。';
    }
    elseif (!Category::find_one_by_id_and_user_id($category_id, $this->user_id))
    {
      $errors['category_id'] = '指定されたカテゴリーは存在しません。';
    }

    if (empty($errors))
    {
      $data = [
        'title' => e($title),
        'category_id' => $category_id,
      ];
      $success = Task::update_task($id, $this->user_id, $data);

      if ($success)
      {
        Session::set_flash('success', 'タスクを更新しました。');
        Response::redirect('tasks');
      }
      else
      {
        $errors['update'] = 'タスクの更新に失敗しました。';
      }
    }

    Session::set_flash('errors', $errors);
    Session::set_flash('form_inputs', Input::post());
    Response::redirect('tasks/edit/' . $id);
  }

  /**
   * タスクを削除
   */
  public function post_delete($id = null)
  {
    if (!Security::check_token())
    {
      Session::set_flash('error', 'ページの有効期限が切れました。もう一度やり直してください。');
      Response::redirect('tasks');
    }

    $task = Task::find_one_by_id_and_user_id($id, $this->user_id);
    if (!$task)
    {
      Session::set_flash('error', '指定されたタスクは見つかりません。');
    }
    else
    {
      $success = Task::delete_task($id, $this->user_id);
      if ($success)
      {
        Session::set_flash('success', 'タスクを削除しました。');
      }
      else
      {
        Session::set_flash('error', 'タスクの削除に失敗しました。');
      }
    }

    Response::redirect('tasks');
  }
}
