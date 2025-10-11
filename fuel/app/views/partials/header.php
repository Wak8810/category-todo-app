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
      <form action="<?php echo Uri::create('logout'); ?>" method="post" class="form-as-inline">
        <?php echo Form::csrf(); ?>
        <button type="submit" class="btn-as-link font-size-sm text-dark-gray text-decoration-none">ログアウト</button>
      </form>
    </div>
  <?php endif; ?>
</header>
