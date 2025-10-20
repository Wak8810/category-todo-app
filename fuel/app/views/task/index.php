<?php
use Fuel\Core\Arr;
use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\Input;
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
      <div id="task-management-container" class="w-100p bg-white p-50 rounded-8 shadow-md border border-gray-medium">
        <?php echo View::forge('partials/flash'); ?>

        <div class="mb-40">
          <h3 class="font-size-lg font-weight-bold mb-20">新規タスク作成</h3>
          <?php $inputs = isset($form_inputs) ? $form_inputs : []; ?>
          <form id="task-create-form" action="<?php echo Uri::create('tasks/create'); ?>" method="POST" class="d-flex align-items-end"
                data-initial-title="<?php echo e(Arr::get($inputs, 'title', '')); ?>"
                data-initial-category-id="<?php echo e(Arr::get($inputs, 'category_id', '')); ?>">
            <?php echo Form::csrf(); ?>
            <div class="flex-grow-1 mr-12">
              <label for="title" class="d-block mb-8 font-weight-600">タスク名</label>
              <input type="text" name="title" id="title" 
                class="w-100p p-y-10 p-x-20 rounded-6 border border-gray-medium" required 
                value="<?php echo e(Arr::get($inputs, 'title', '')); ?>"
                data-bind="value: title, valueUpdate: 'afterkeydown'">
              <div class="font-size-xs mt-5 text-red min-h-1-2em" data-bind="visible: titleError, text: titleError"></div>
            </div>
            <div class="mr-12">
              <label for="category_id" class="d-block mb-8 font-weight-600">カテゴリー</label>
              <select name="category_id" id="category_id" class="p-y-10 p-x-20 rounded-6 border border-gray-medium h-42px"
                      data-bind="value: selectedCategoryId">
                <?php foreach ($categories as $category): ?>
                  <option value="<?php echo $category['id']; ?>" <?php echo Arr::get($inputs, 'category_id') == $category['id'] ? 'selected' : ''; ?>><?php echo e($category['short_name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <button type="submit" class="p-y-10 p-x-25 rounded-6 border-none bg-blue text-white font-weight-bold cursor-pointer h-42px"
                      data-bind="enable: isFormValid">作成</button>
            </div>
          </form>
        </div>

        <hr class="mb-40">

        <div id="task-app" 
          data-todo-tasks='<?php echo json_encode($todo_tasks, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>' 
          data-done-tasks='<?php echo json_encode($done_tasks, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>'
          data-categories='<?php echo json_encode($categories, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>

          <div class="mb-40">
            <button type="button" class="d-inline-block p-y-10 p-x-25 rounded-25 text-dark-gray font-size-base font-weight-600 border border-gray-light cursor-pointer mb-12" 
              data-bind="click: toggleCategoryFilter, css: { 'bg-white': !isCategoryFilterVisible(), 'bg-gray-extra-light': isCategoryFilterVisible(), 'shadow-inner': isCategoryFilterVisible() }">
              カテゴリーで絞り込み
            </button>
            <div data-bind="visible: isCategoryFilterVisible">
              <!-- 2重ループでボタンを作成 -->
              <div data-bind="foreach: chunkedCategoryButtons">
                <div class="d-flex mb-15" data-bind="foreach: $data">
                  <button class="p-y-8 rounded-25 border-none cursor-pointer mr-15 w-120 whitespace-nowrap overflow-hidden text-ellipsis text-center" 
                          data-bind="click: toggle, style: { backgroundColor: colorCode, opacity: isSelected() ? 1 : 0.6 }, text: shortName, attr: { title: name }">
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="d-flex">
            <div class="w-1-2 mr-10">
              <h3 class="font-size-lg font-weight-bold mb-20">TODO</h3>
              <div data-bind="foreach: todoTasks">
                <div class="d-flex align-items-center p-15 rounded-6 mb-20 text-white" data-bind="style: { backgroundColor: category_color_code }">
                  <input type="checkbox" class="mr-12" data-bind="checked: is_completed(), click: $parent.toggleTask">
                  <a class="text-white text-decoration-none flex-grow-1 p-y-10" data-bind="attr: { href: editUrl, title: title }, text: short_title"></a>
                </div>
              </div>
              <p data-bind="visible: todoTasks().length === 0">Todoタスクはありません。</p>
            </div>
  
            <div class="w-1-2 ml-10 mt-0">
              <h3 class="font-size-lg font-weight-bold mb-20">DONE</h3>
              <div data-bind="foreach: doneTasks">
                <div class="d-flex align-items-center p-15 rounded-6 mb-20 text-white" data-bind="style: { backgroundColor: category_color_code }">
                  <input type="checkbox" class="mr-12" data-bind="checked: is_completed(), click: $parent.toggleTask">
                  <a class="text-white text-decoration-none flex-grow-1 p-y-10" data-bind="attr: { href: editUrl, title: title }">
                    <s data-bind="text: short_title"></s>
                  </a>
                </div>
              </div>
              <p data-bind="visible: doneTasks().length === 0">Doneタスクはありません。</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php echo Asset::js('index_task.js'); ?>
</body>
</html>