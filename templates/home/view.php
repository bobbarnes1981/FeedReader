<h2>Home</h2>
<div class="list-group">
<?php foreach ($channels as $channel) { ?>
    <a class="list-group-item<?php echo ($channel->unread?' unread':''); ?>" href="/channel/view?id=<?php echo $channel->id; ?>">
        <?php echo $channel->title; ?>
        <?php if ($channel->unread) { ?>
        <span class="badge"><?php echo $channel->unread; ?></span>
        <?php } ?>
    </a>
<?php } ?>
</div>
