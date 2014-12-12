<form class="form-base form-unsubscribe" action="/subscription/del?id=<?php echo $channel->id; ?>" method="post">
    <h2 class="form-unsubscribe-heading">Unubscribe</h2>
    <label for="">Are you sure?</label>
    <input type="hidden" name="unsubscribe" value="true" />
    <button class="btn btn-large btn-success btn-block" type="submit">Yes</button>
</form>
<form class="form-base form-unsubscribe" action="/channel/view?id=<?php echo $channel->id; ?>" method="post">
    <button class="btn btn-large btn-danger btn-block" type="submit">No</button>
</form>
