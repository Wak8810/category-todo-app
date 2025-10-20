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
         data-category='<?php echo json_encode($category, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
      <h2 class="mb-20 font-size-xl mt-30">カテゴリー編集</h2>

      <?php echo View::forge('partials/flash'); ?>

      <form id="update-category-form" method="post" data-bind="attr: { action: updateUrl }">
        <?php echo Form::csrf(); ?>
        <div class="mb-20">
          <label for="name" class="d-block mb-8 font-weight-600">カテゴリー名</label>
          <textarea id="name" name="name" class="w-100p p-y-8 p-x-20 rounded-4 border border-gray-light resize-vertical" rows="3" data-bind="value: name, valueUpdate: 'afterkeydown'" required><?php echo e(Arr::get($inputs, 'name', $category['name'])); ?></textarea>
          <div class="font-size-xs mt-5 text-red min-h-1-2em" data-bind="visible: nameError, text: nameError"></div>
        </div>
        
        <div class="mb-20">
          <label for="color_code" class="d-block mb-8 font-weight-600">カラー</label>
          <div class="d-flex align-items-center">
              <input type="color" name="color_code" id="color_code" class="rounded-6 h-42px w-100px" value="<?php echo e(Arr::get($inputs, 'color_code', $category['color_code'])); ?>" data-bind="value: colorCode" required>
          </div>
          <div class="font-size-xs mt-5 text-red min-h-1-2em" data-bind="visible: colorCodeError, text: colorCodeError"></div>
        </div>
      </form>

      <div class="d-flex justify-content-between align-items-center mt-30">
        <form action="<?php echo Uri::create('categories/delete/' . $category['id']); ?>" method="POST" onsubmit="return confirm('削除した場合、そのカテゴリーのタスクも削除されますがよろしいですか？');">
          <?php echo Form::csrf(); ?>
          <button type="submit" class="p-y-10 p-x-20 rounded-6 border-none bg-red text-white font-weight-bold cursor-pointer">削除</button>
        </form>
        <div>
          <a href="/categories" class="p-y-10 p-x-15 text-decoration-none cursor-pointer font-size-base rounded-4 bg-gray-dark text-white mr-12">キャンセル</a>
          <button type="submit" form="update-category-form" class="p-y-10 p-x-15 text-decoration-none cursor-pointer font-size-base rounded-4 border-none mr-12 bg-blue text-white" data-bind="enable: isFormValid">更新</button>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-latest.js"></script>
  <?php echo Asset::js('edit_category.js'); ?>
</body>
</html>
