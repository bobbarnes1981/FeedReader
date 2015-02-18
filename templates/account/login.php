<?php echo $form->begin(); ?>
<h2 class="form-login-heading">Please sign in</h2>
<label for="">Username</label>
<?php echo $form->render('username'); ?>
<label for="">Password</label>
<?php echo $form->render('password'); ?>
<?php echo $form->render('Login'); ?>
<?php echo $form->end(); ?>
