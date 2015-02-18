<h2>Log</h2>
<div class="well">
    <?php if ($filter == 'error') { ?>
	<?php echo \Html::link('All', \Url::get('log', 'view', array('page' => $page, 'filter' => '')), array('class' => 'btn btn-info')); ?>
    <?php } else { ?>
	<?php echo \Html::link('Errors', \Url::get('log', 'view', array('page' => $page, 'filter' => 'error')), array('class' => 'btn btn-danger')); ?>
    <?php } ?>
    <?php echo $pagination; ?>
</div>
<div class="list-group">
<?php foreach ($logs as $log) { ?>
    <?php 
    switch($log->type) { 
        case 'info': $class = 'info'; break;
        case 'error': $class = 'danger'; break;
        default: $class = 'default'; break;
    }
    ?>
    <div class="list-group-item alert-<?php echo $class; ?> font-monospace">
        <span><?php echo $log->date; ?></span>
        <span><?php echo $log->type; ?></span>
        <span><?php echo $log->message; ?></span>
    </div>
<?php } ?>
</div>
