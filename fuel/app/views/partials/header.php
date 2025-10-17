<?php
use Fuel\Core\Uri;
use Fuel\Core\Session;
use Auth\Auth;
use Fuel\Core\Form;
?>
<header class="p-y-15 p-x-20 bg-green-light font-size-lg font-weight-bold text-dark-gray d-flex justify-content-between align-items-center">
  <span>TODOAPP</span>
  <?php if (Auth::check()): ?>
    <div>
      <span class="font-size-sm" style="margin-right: 20px;">ユーザー名：<?php echo e(Session::get('username')); ?></span>
      <form action="<?php echo Uri::create('logout'); ?>" method="post" class="d-inline">
        <?php echo Form::csrf(); ?>
        <button type="submit" class="bg-none border-none p-0 cursor-pointer font-size-sm text-dark-gray text-decoration-none">ログアウト</button>
      </form>
    </div>
  <?php endif; ?>
</header>
