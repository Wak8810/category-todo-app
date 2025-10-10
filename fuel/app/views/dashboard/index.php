<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ダッシュボード</title>
  <?php echo Asset::css('style.css'); ?>
</head>
<body class="d-flex flex-column min-h-100vh">

  <header class="p-y-15 p-x-20 bg-green-light font-size-lg font-weight-bold text-dark-gray d-flex justify-content-between align-items-center">
    <span>TODOAPP</span>
    <a href="<?php echo Uri::create('login/logout'); ?>" class="font-size-sm text-dark-gray text-decoration-none">ログアウト</a>
  </header>

  <div class="flex-grow-1 d-flex justify-content-center align-items-center p-20">
    <div class="w-100 max-w-480 bg-white p-50 rounded-8 shadow-md border border-gray-medium">
      <h2 class="text-left font-size-xl mb-40 text-dark-gray font-weight-600">ようこそ、<?php echo e($username); ?> さん</h2>
      
      <div class="mb-25">
        <p class="font-weight-bold">ユーザー情報</p>
        <p>ユーザー名: <?php echo e($username); ?></p>
        <p>メールアドレス: <?php echo e($email); ?></p>
      </div>

    </div>
  </div>

</body>
</html>
