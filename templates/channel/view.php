<h2><?php echo $channel->title; ?></h2>
<div class="well">
    <a href="/home/view" class="btn btn-info">Channels</a>
    <?php if (Session::Get('all', false) != 'true') { ?>
    <a href="/channel/view?id=<?php echo $channel->id; ?>&all=true" class="btn btn-success">Show All</a>
    <?php } else { ?>
    <a href="/channel/view?id=<?php echo $channel->id; ?>&all=false" class="btn btn-success">Show Unread</a>
    <?php } ?>
    <a href="/subscription/del?id=<?php echo $channel->id; ?>" class="btn btn-danger">Unsubscribe</a>
    <?php echo $pagination; ?>
</div>
<div class="list-group">
<?php foreach ($items as $item) { ?>
    <div class="list-group-item">
        <?php if ($item->starred) { ?>
            <span class="glyphicon glyphicon-star"></span>
        <?php } else { ?>
            <span class="glyphicon"></span>
        <?php } ?>
        <a href="/item/view?id=<?php echo $item->id; ?>" class="-btn -btn-default -btn-xs">
            <?php echo $item->title;?>
        </a>
        <span class="label label-default"><?php echo $item->pubdate; ?></span>
        <?php if ($item->unread) { ?>
        <a class="btn btn-danger btn-xs pull-right" href="/item/state?id=<?php echo $item->id; ?>&unread=false&return=channel&page=<?php echo $page; ?>">Mark Read</a>
        <?php } else { ?>
        <a class="btn btn-success btn-xs pull-right" href="/item/state?id=<?php echo $item->id; ?>&unread=true&return=channel&page=<?php echo $page; ?>">Mark Unread</a>
        <?php } ?>
    </div>
<?php } ?>
</div>
