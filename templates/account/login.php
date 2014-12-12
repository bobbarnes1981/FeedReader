<form class="form-base form-login" action="/account/login" method="post">
    <h2 class="form-login-heading">Please sign in</h2>
    <label for="">Username</label>
    <input type="text" name="username" value="<?php echo $username; ?>" class="input-block-level" placeholder="Username" /><br/>
    <label for="">Password</label>
    <input type="password" name="password" value="<?php echo $password; ?>" class="input-block-level" placeholder="Password" /><br/>
    <button class="btn btn-large btn-primary btn-block" type="submit">Login</button>
</form>
