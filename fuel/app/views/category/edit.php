<?php
use Fuel\Core\Arr;
use Fuel\Core\Asset;
use Fuel\Core\View;
?>
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
    <?php
      $inputs = isset($form_inputs) ? $form_inputs : [];
    ?>
    <div id="edit-category-view" class="max-w-600 m-x-auto mt-30 p-20 bg-white rounded-8 shadow-md"
         data-initial-name="<?php echo e(Arr::get($inputs, 'name', $category['name'])); ?>"
         data-initial-color-code="<?php echo e(Arr::get($inputs, 'color_code', $category['color_code'])); ?>">
      <h2 class="mb-20 font-size-xl mt-30">カテゴリー編集</h2>

      <?php echo View::forge('partials/flash'); ?>

      <form method="post" data-bind="attr: { action: updateUrl }">
        <div class="mb-20">
          <label for="name" class="d-block mb-8 font-weight-600">カテゴリー名</label>
          <textarea id="name" name="name" class="w-100p p-y-8 p-x-20 rounded-4 border border-gray-light resize-vertical" rows="3" data-bind="value: name, valueUpdate: 'afterkeydown'" required><?php echo e(Arr::get($inputs, 'name', $category['name'])); ?></textarea>
          <div class="font-size-xs mt-5 text-red min-h-1-2em" data-bind="visible: nameError, text: nameError"></div>
        </div>
        
        <div class="mb-20">
          <label for="color_code" class="d-block mb-8 font-weight-600">カラー</label>
          <div class="d-flex align-items-center">
              <input type="color" name="color_code" id="color_code" class="rounded-6" style="height: 42px; width: 100px;" value="<?php echo e(Arr::get($inputs, 'color_code', $category['color_code'])); ?>" data-bind="value: colorCode" required>
          </div>
          <div class="font-size-xs mt-5 text-red min-h-1-2em" data-bind="visible: colorCodeError, text: colorCodeError"></div>
        </div>

        <div class="mt-30 text-center">
          <button type="submit" class="p-y-10 p-x-15 text-decoration-none cursor-pointer font-size-base rounded-4 border-none mr-12 bg-blue text-white" data-bind="enable: isFormValid">更新</button>
          <a href="/categories" class="p-y-10 p-x-15 text-decoration-none cursor-pointer font-size-base rounded-4 bg-gray-dark text-white">キャンセル</a>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-latest.js"></script>
  <?php echo Asset::js('edit_category.js'); ?>
</body>
</html>
