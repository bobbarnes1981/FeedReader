<h2>Starred</h2>
<div class="well">
	<?php echo Html::link('Channels', Url::get('home', 'view'), array('class' => 'btn btn-info')); ?>
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
		<?php echo Html::link($item->title, Url::get('item', 'view', array('id' => $item->id)), array('class' => '-btn -btn-default -btn-xs')); ?>
            <?php echo $item->title;?>
        </a>
        <span class="label label-default"><?php echo $item->pubdate; ?></span>
		<?php if ($item->unread) { ?>
        <a class="btn btn-danger btn-xs pull-right" href="/item/state?id=<?php echo $item->id; ?>&unread=false&return=<?php echo urlencode('/item/starred'); ?>">Mark Read</a>
		<?php echo Html::link('Mark Read', Url::get('item', 'state', array('id' => $item->id, 'unread' => 'false', 'return' => urlencode(Url::get('item', 'starred')))), array('class' => 'btn btn-danger btn-xs pull-right')); ?>
        <?php } else { ?>
		<?php echo Html::link('Mark Unread', Url::get('item', 'state', array('id' => $item->id, 'unread' => 'true', 'return' => urlencode(Url::get('item', 'starred')))), array('class' => 'btn btn-success btn-xs pull-right')); ?>
        <?php } ?>
    </div>
<?php } ?>
</div>
