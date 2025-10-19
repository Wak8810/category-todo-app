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
  <title>カテゴリー管理</title>
  <?php echo Asset::css('style.css'); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-latest.js"></script>
</head>
<body class="d-flex flex-column min-h-100vh">

  <?php echo View::forge('partials/header'); ?>
  <div class="p-x-20">
    <div class="mt-30">
      <a href="<?php echo Uri::create('tasks'); ?>" 
        class="d-inline-block p-y-10 p-x-25 rounded-25 text-decoration-none text-dark-gray font-size-base font-weight-600 border bg-white border-gray-light cursor-pointer">タスク一覧</a>
      <a href="<?php echo Uri::create('categories'); ?>" 
        class="d-inline-block p-y-10 p-x-25 rounded-25 text-decoration-none text-dark-gray font-size-base font-weight-600 border bg-lime border-lime cursor-pointer">カテゴリー一覧</a>
    </div>
    <div class="flex-grow-1">
      <div class="w-100p bg-white p-50 rounded-8 shadow-md border border-gray-medium">
        <?php echo View::forge('partials/flash'); ?>

        <div class="mb-40">
          <h3 class="font-size-lg font-weight-bold mb-20">新規カテゴリー作成</h3>
          <?php
            $inputs = isset($form_inputs) ? $form_inputs : [];
          ?>
          <form id="category-create-form" action="<?php echo Uri::create('categories/create'); ?>" method="POST" class="d-flex align-items-end"
                data-initial-name="<?php echo e(Arr::get($inputs, 'name', '')); ?>"
                data-initial-color-code="<?php echo e(Arr::get($inputs, 'color_code', '#000000')); ?>">
            <?php echo Form::csrf(); ?>
            <div class="mr-12">
              <label for="name" class="d-block mb-8 font-weight-600">カテゴリー名</label>
              <input type="text" name="name" id="name" class="p-y-10 p-x-20 rounded-6 border border-gray-medium" required 
                     value="<?php echo e(Arr::get($inputs, 'name', '')); ?>"
                     data-bind="value: name, valueUpdate: 'afterkeydown'">
              <div class="font-size-xs mt-5 text-red min-h-1-2em" data-bind="visible: nameError, text: nameError"></div>
            </div>
            <div class="mr-12">
              <label for="color_code" class="d-block mb-8 font-weight-600">カラー</label>
              <div class="d-flex align-items-center">
                <input type="color" name="color_code" id="color_code" class="rounded-6 border border-gray-medium" style="height: 42px; width: 100px;"
                       value="<?php echo e(Arr::get($inputs, 'color_code', '#000000')); ?>"
                       data-bind="value: colorCode">
              </div>
              <div class="font-size-xs mt-5 text-red min-h-1-2em" data-bind="visible: colorCodeError, text: colorCodeError"></div>
            </div>
            <div>
              <button type="submit" class="p-y-10 p-x-25 rounded-6 border-none bg-blue text-white font-weight-bold cursor-pointer" style="height: 42px;" data-bind="enable: isFormValid">作成</button>
            </div>
          </form>
        </div>

        <hr class="mb-40">

        <div>
          <h3 class="font-size-lg font-weight-bold mb-20">カテゴリー一覧</h3>
          <table class="w-100p text-left table-fixed border-separate border-spacing-y-5">
            <thead>
              <tr class="border-bottom">
                <th class="p-y-10 font-weight-600 w-15p">カラー</th>
                <th class="p-y-10 font-weight-600 w-70p">カテゴリー名</th>
                <th class="p-y-10 font-weight-600 w-15p">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($categories)) : ?>
                <?php foreach ($categories as $category) : ?>
                  <tr class="bg-gray-light">
                    <td class="p-y-10 d-flex justify-content-center">
                      <div class="w-60p h-50" style="background-color: <?php echo e($category['color_code']); ?>;"></div>
                    </td>
                    <td class="p-y-10">
                      <!---長すぎるのを対処--->
                      <?php echo e(mb_strlen($category['name']) > 60 ? mb_substr($category['name'], 0, 70) . '...' : $category['name']); ?>
                    </td>
                    <td class="p-y-10 text-center">
                      <a href="<?php echo Uri::create('categories/edit/' . $category['id']); ?>" class="d-inline-block p-y-10 p-x-20 rounded-6 border border-gray-light bg-white text-blue text-decoration-none mr-12">編集</a>
                      <form action="<?php echo Uri::create('categories/delete/' . $category['id']); ?>" method="POST" class="d-inline">
                        <?php echo Form::csrf(); ?>
                        <button type="submit" class="p-y-10 p-x-20 rounded-6 border border-red-light bg-red-light text-red-dark cursor-pointer" onclick="return confirm('本当に削除しますか？この操作は元に戻せません。');">削除</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="3" class="p-y-10 text-center">カテゴリーはまだ登録されていません。</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
  <?php echo Asset::js('index_category.js'); ?>
</body>
</html>
