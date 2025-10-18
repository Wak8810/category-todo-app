<?php
use Fuel\Core\Arr;
use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\Uri;
use Fuel\Core\View;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>タスク管理</title>
  <?php echo Asset::css('style.css'); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-latest.js"></script>
</head>
<body class="d-flex flex-column min-h-100vh">

  <?php echo View::forge('partials/header'); ?>

  <div class="p-x-20">
    <div class="mt-30">
      <a href="<?php echo Uri::create('tasks'); ?>" 
        class="d-inline-block p-y-10 p-x-25 rounded-25 text-decoration-none text-dark-gray font-size-base font-weight-600 border bg-lime border-lime cursor-pointer">タスク一覧</a>
      <a href="<?php echo Uri::create('categories'); ?>" 
        class="d-inline-block p-y-10 p-x-25 rounded-25 text-decoration-none text-dark-gray font-size-base font-weight-600 border bg-white border-gray-light cursor-pointer">カテゴリー一覧</a>
    </div>

    <div class="flex-grow-1">
      <div class="w-100p bg-white p-50 rounded-8 shadow-md border border-gray-medium">
        <?php echo View::forge('partials/flash'); ?>

        <div class="mb-40">
          <h3 class="font-size-lg font-weight-bold mb-20">新規タスク作成</h3>
          <?php $inputs = isset($form_inputs) ? $form_inputs : []; ?>
          <form id="task-create-form" action="<?php echo Uri::create('tasks/create'); ?>" method="POST" class="d-flex align-items-end">
            <?php echo Form::csrf(); ?>
            <div class="flex-grow-1 mr-12">
              <label for="title" class="d-block mb-8 font-weight-600">タスク名</label>
              <input type="text" name="title" id="title" class="w-100p p-y-10 p-x-20 rounded-6 border border-gray-medium" required value="<?php echo e(Arr::get($inputs, 'title', '')); ?>">
            </div>
            <div class="mr-12">
              <label for="category_id" class="d-block mb-8 font-weight-600">カテゴリー</label>
              <select name="category_id" id="category_id" class="p-y-10 p-x-20 rounded-6 border border-gray-medium" style="height: 42px;">
                <?php foreach ($categories as $category): ?>
                  <option value="<?php echo $category['id']; ?>" <?php echo Arr::get($inputs, 'category_id') == $category['id'] ? 'selected' : ''; ?>><?php echo e($category['name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <button type="submit" class="p-y-10 p-x-25 rounded-6 border-none bg-blue text-white font-weight-bold cursor-pointer" style="height: 42px;">作成</button>
            </div>
          </form>
        </div>

        <hr class="mb-40">

        <div class="mb-40">
          <form action="<?php echo Uri::create('tasks'); ?>" method="GET" class="d-flex align-items-center">
            <label for="filter_category_id" class="d-block mr-12 font-weight-600">カテゴリーで絞り込み:</label>
            <select name="category_id" id="filter_category_id" class="p-y-10 p-x-20 rounded-6 border border-gray-medium mr-12">
              <option value="">すべてのカテゴリー</option>
              <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>" <?php echo Input::get('category_id') == $category['id'] ? 'selected' : ''; ?>><?php echo e($category['name']); ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="p-y-10 p-x-25 rounded-6 border-none bg-gray-dark text-white font-weight-bold cursor-pointer">絞り込み</button>
          </form>
        </div>

        <div class="d-flex">
          <div class="w-1-2 mr-10">
            <h3 class="font-size-lg font-weight-bold mb-20">TODO</h3>
            <div id="todo-list" data-tasks='<?php echo json_encode($todo_tasks); ?>' data-bind="foreach: tasks">
              <!-- Task Item Template -->
              <div class="d-flex align-items-center p-15 rounded-6 mb-10 text-white" data-bind="style: { backgroundColor: category_color_code }">
                <form method="POST" class="mr-12" data-bind="attr: { action: toggleUrl }">
                  <input type="checkbox" onchange="this.form.submit()">
                </form>
                <a class="text-white text-decoration-none flex-grow-1" data-bind="attr: { href: editUrl }, text: title"></a>
              </div>
            </div>
            <?php if (empty($todo_tasks)): ?>
              <p>Todoタスクはありません。</p>
            <?php endif; ?>
          </div>

          <div class="w-1-2 ml-10 mt-0">
            <h3 class="font-size-lg font-weight-bold mb-20">DONE</h3>
            <div id="done-list" data-tasks='<?php echo json_encode($done_tasks); ?>' data-bind="foreach: tasks">
              <!-- Task Item Template -->
              <div class="d-flex align-items-center p-15 rounded-6 mb-10 text-white" data-bind="style: { backgroundColor: category_color_code }">
                <form method="POST" class="mr-12" data-bind="attr: { action: toggleUrl }">
                  <input type="checkbox" onchange="this.form.submit()" checked>
                </form>
                <a class="text-white text-decoration-none flex-grow-1" data-bind="attr: { href: editUrl }">
                  <s data-bind="text: title"></s>
                </a>
              </div>
            </div>
            <?php if (empty($done_tasks)): ?>
              <p>Doneタスクはありません。</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php echo Asset::js('task-list.js'); ?>
</body>
</html>