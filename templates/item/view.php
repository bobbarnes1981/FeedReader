<h2><?php echo $channel->title; ?></h2>

<div class="well">
    <a href="/channel/view?id=<?php echo $channel->id; ?>" class="btn btn-info">Items</a>
    <a class="" href="<?php echo $item->link; ?>"><?php echo $item->link; ?></a>
    <?php if ($item->unread) { ?>
    <a class="btn btn-danger pull-right" href="/item/state?id=<?php echo $item->id; ?>&return=channel&unread=false">Mark Read</a></li>
    <?php } else { ?>
    <a class="btn btn-success pull-right" href="/item/state?id=<?php echo $item->id; ?>&return=item&unread=true">Mark Unread</a></li>
    <?php } ?>
</div>

<?php echo $pager; ?>

<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">
        <?php if ($item->starred) { ?>
        <a href="/item/state?id=<?php echo $item->id; ?>&starred=false&return=item"><span class="glyphicon glyphicon-star"></span></a>
        <?php } else { ?>
        <a href="/item/state?id=<?php echo $item->id; ?>&starred=true&return=item"><span class="glyphicon glyphicon-star-empty"></span></a>
        <?php } ?>
        <?php echo $item->title; ?> (<?php echo $item->pubdate; ?>)</h3>
    </div>
    <?php echo $item->description; ?>
</div>
<hr />
<div class="well"><?php echo $item->content; ?></div>
