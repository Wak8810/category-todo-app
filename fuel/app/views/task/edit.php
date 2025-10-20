<?php
use Fuel\Core\Arr;
use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\Uri;
use Fuel\Core\View;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>タスク編集</title>
  <?php echo Asset::css('style.css'); ?>
</head>
<body>
  <?php echo View::forge('partials/header'); ?>

  <div class="p-x-20">
    <div id="edit-task-view" class="max-w-600 m-x-auto mt-30 p-50 bg-white rounded-8 shadow-md"
         data-task='<?php echo json_encode($task, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
      <h2 class="mb-40 font-size-xl font-weight-bold">タスク編集</h2>

      <?php echo View::forge('partials/flash'); ?>

      <?php $inputs = isset($form_inputs) ? $form_inputs : []; ?>

      <form id="update-task-form" method="POST" data-bind="attr: { action: updateUrl }">
        <?php echo Form::csrf(); ?>
        <div class="mb-25">
          <label for="title" class="d-block mb-8 font-weight-600">タスク名</label>
          <textarea name="title" id="title" class="w-100p p-y-10 p-x-20 rounded-6 border border-gray-medium resize-vertical" rows="3" required data-bind="value: title, valueUpdate: 'afterkeydown'"><?php echo e(Arr::get($inputs, 'title', $task['title'])); ?></textarea>
          <div class="font-size-xs mt-5 text-red min-h-1-2em" data-bind="visible: titleError, text: titleError"></div>
        </div>

        <div class="mb-40">
          <label for="category_id" class="d-block mb-8 font-weight-600">カテゴリー</label>
          <select name="category_id" id="category_id" class="w-100p p-y-10 p-x-20 rounded-6 border border-gray-medium" data-bind="value: category_id">
            <?php foreach ($categories as $category):
              $selected_category = Arr::get($inputs, 'category_id', $task['category_id']);
              $is_selected = ($selected_category == $category['id']);
            ?>
              <option value="<?php echo $category['id']; ?>" <?php echo $is_selected ? 'selected' : ''; ?>><?php echo e($category['name']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </form>

      <div class="d-flex justify-content-between align-items-center">
        <form action="<?php echo Uri::create('tasks/delete/' . $task['id']); ?>" method="POST" onsubmit="return confirm('本当にこのタスクを削除しますか？');">
          <?php echo Form::csrf(); ?>
          <button type="submit" class="p-y-10 p-x-20 rounded-6 border-none bg-red text-white font-weight-bold cursor-pointer">削除</button>
        </form>
        <div>
          <a href="<?php echo Uri::create('tasks'); ?>" class="p-y-10 p-x-20 rounded-6 text-decoration-none bg-gray-dark text-white mr-12">キャンセル</a>
          <button type="submit" form="update-task-form" class="p-y-10 p-x-20 rounded-6 border-none bg-blue text-white font-weight-bold cursor-pointer" data-bind="enable: isFormValid">更新</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-latest.js"></script>
  <?php echo Asset::js('edit_task.js'); ?>
</body>
</html>