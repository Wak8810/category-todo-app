<?php
use Fuel\Core\Session;
?>

<?php if ($success = Session::get_flash('success')) : ?>
  <div class="p-15 rounded-4 mb-20 text-green-dark bg-green-pale border border-green-pale"><?php echo e($success); ?></div>
<?php endif; ?>
<?php if ($error = Session::get_flash('error')) : ?>
  <div class="p-15 rounded-4 mb-20 text-red-dark bg-red-light border border-red-light"><?php echo e($error); ?></div>
<?php endif; ?>
<?php if ($errors = Session::get_flash('errors')) : ?>
  <div class="p-15 mb-20 rounded-4 text-red-dark bg-red-light border border-red-light">
    <ul>
      <?php foreach ($errors as $error_message) : ?>
        <li><?php echo e($error_message); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>