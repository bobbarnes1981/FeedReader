<form class="form-base form-subscribe" action="<?php echo Url::get('subscription', 'add'); ?>" method="post">
    <h2 class="form-subscribe-heading">Subscribe</h2>
    <label for="">Url</label>
    <input class="input-block-level" type="text" name="url" value="<?php echo $url; ?>" placeholder="Feed URL"/><br/>
    <button class="btn btn-large btn-primary btn-block" type="submit">Subscribe</button>
</form>
