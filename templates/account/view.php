<form class="form-base form-account" action="/account/view" method="post">
    <h2 class="form-account-heading">Account</h2>
    <label for="">Username</label>
    <input type="text" name="username" value="<?php echo $user->username; ?>" class="input-block-level" placeholder="Username" readonly="readonly" /><br/>
    <label for="">Email</label>
    <input type="text" name="email" value="<?php echo $email; ?>" class="input-block-level" placeholder="Email" /><br/>
    <label for="">New Password</label>
    <input type="password" name="new_password" value="<?php echo $new_password; ?>" class="input-block-level" placeholder="New Password" /><br/>
    <label for="">Old Password</label>
    <input type="password" name="password" value="<?php echo ''; ?>" class="input-block-level" placeholder="Old Password" />
    <button class="btn btn-large btn-primary btn-block" type="submit">Update</button>
</form>
