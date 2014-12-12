<form class="form-base form-settings" action="/settings/view" method="post">
    <h2 class="form-settings-heading">Settings</h2>
    <label for="">Test A<br/><span class="text-muted">Just a test option.</span></label><br/>
    <input type="checkbox" name="test_a" value="<?php echo $test_a_opt; ?>" <?php echo ($test_a_checked->value?'checked="checked"':''); ?> class="input-block-level" /><br/>
    <label for="">Test B<br/><span class="text-muted">Just another test selection.</span></label><br/>
    <?php foreach ($test_b_opt as $id => $label) { ?>
    <input type="radio" name="test_b" value="<?php echo $id; ?>" <?php echo ($test_b_checked->value==$id?'checked="checked"':''); ?> class="input-block-level" /><?php echo $label; ?><br/>
    <?php } ?>
    <button class="btn btn-large btn-primary btn-block" type="submit">Save</button>
</form>
