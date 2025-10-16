<?php
use Fuel\Core\Session;
?>

<?php if ($success = Session::get_flash('success')) : ?>
  <div class="alert-success p-15 rounded-4 mb-20"><?php echo $success; ?></div>
<?php endif; ?>
<?php if ($error = Session::get_flash('error')) : ?>
  <div class="alert-danger p-15 rounded-4 mb-20"><?php echo $error; ?></div>
<?php endif; ?>
<?php if ($errors = Session::get_flash('errors')) : ?>
  <div class="alert-danger p-15 mb-20 rounded-4">
    <ul>
      <?php foreach ($errors as $error_message) : ?>
        <li><?php echo $error_message; ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>
