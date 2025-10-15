<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>カテゴリー編集</title>
  <?php echo Asset::css('style.css'); ?>
</head>
<body>
  <?php echo View::forge('partials/header'); ?>
  <div class="p-x-20">
    <div id="edit-category-view" class="max-w-600 m-x-auto mt-30 p-20 bg-white rounded-8 shadow-md" data-category='<?php echo json_encode($category); ?>'>
      <h2 class="mb-20 font-size-xl mt-30">カテゴリー編集</h2>

      <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert-danger p-15 rounded-4 mb-20">
          <p class="m-0 font-weight-bold">以下のエラーを修正してください:</p>
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?php echo $error; ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" data-bind="attr: { action: updateUrl }">
        <div class="mb-20">
          <label for="name" class="d-block mb-8 font-weight-600">カテゴリー名</label>
          <textarea id="name" name="name" class="w-100p p-y-8 p-x-20 rounded-4 border border-gray-light resize-vertical" rows="3" data-bind="value: name, valueUpdate: 'afterkeydown'" required></textarea>
          <div class="font-size-xs mt-5 text-red min-h-1-2em" data-bind="visible: nameError, text: nameError"></div>
        </div>
        
        <div class="mb-20">
          <label for="color_code" class="d-block mb-8 font-weight-600">カラー</label>
          <div class="d-flex align-items-center">
              <input type="color" name="color_code" id="color_code" class="rounded-6" style="height: 42px; width: 100px;" data-bind="value: colorCode" required>
          </div>
          <div class="font-size-xs mt-5 text-red min-h-1-2em" data-bind="visible: colorCodeError, text: colorCodeError"></div>
        </div>

        <div class="mt-30 text-center">
          <button type="submit" class="p-y-10 p-x-15 text-decoration-none cursor-pointer font-size-base btn-primary rounded-4 border-none mr-12" data-bind="enable: isFormValid">更新</button>
          <a href="/categories" class="p-y-10 p-x-15 text-decoration-none cursor-pointer font-size-base btn-default rounded-4">キャンセル</a>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-latest.js"></script>
  <?php echo Asset::js('edit_category.js'); ?>
</body>
</html>
