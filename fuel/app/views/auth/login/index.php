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
  <title>ログイン</title>
  <?php echo Asset::css('style.css'); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-latest.js"></script>
</head>
<body class="d-flex flex-column min-h-100vh">

  <?php echo View::forge('partials/header'); ?>

  <?php
    $inputs = isset($form_inputs) ? $form_inputs : [];
  ?>

  <div class="flex-grow-1 d-flex justify-content-center align-items-center p-20">
    <div class="w-100p max-w-480 bg-white p-50 rounded-8 shadow-md border border-gray-medium">
      <h2 class="text-left font-size-xl mb-40 text-dark-gray font-weight-600">ログイン</h2>

      <?php echo View::forge('partials/flash'); ?>
      <form id="login-form" action="<?php echo Uri::create('login/login'); ?>" method="POST"
            data-initial-email="<?php echo e(Arr::get($inputs, 'email', '')); ?>">
        <?php echo Form::csrf(); ?>

        <div class="mb-25">
          <label for="form_email" class="d-block mb-8 font-size-sm font-weight-500 text-gray-dark">メールアドレス</label>
          <input type="email" name="email" id="form_email" placeholder="メールアドレス"
                 class="w-100p p-15 rounded-6 border border-gray-light font-size-base placeholder-gray-light"
                 value="<?php echo e(Arr::get($inputs, 'email', '')); ?>"
                 data-bind="value: email, valueUpdate: 'blur'">
          <div class="font-size-xs mt-5 text-red min-h-1-2em" data-bind="visible: emailError, text: emailError"></div>
        </div>

        <div class="mb-25">
          <label for="form_password" class="d-block mb-8 font-size-sm font-weight-500 text-gray-dark">パスワード</label>
          <input type="password" name="password" id="form_password" placeholder="パスワード"
                 class="w-100p p-15 rounded-6 border border-gray-light font-size-base placeholder-gray-light"
                 data-bind="value: password, valueUpdate: 'blur'">
        </div>

        <div class="d-block text-left mb-30 font-size-sm">
          <a href="<?php echo Uri::create('register'); ?>" class="text-blue text-decoration-none">アカウント登録はこちら</a>
        </div>
        
        

        <div class="d-flex justify-content-center mt-10">
          <button type="submit" class="w-80p p-y-15 p-x-20 rounded-25 cursor-pointer font-size-base font-weight-600 text-center text-decoration-none bg-blue text-white border-none" data-bind="enable: isFormValid">ログイン</button>
        </div>
      </form>
    </div>
  </div>

  <?php echo Asset::js('login.js'); ?>
</body>
</html>
