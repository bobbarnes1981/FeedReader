<h2>Log</h2>
<div class="well">
    <?php if ($filter == 'error') { ?>
    <a href="/log/view?page=<?php echo $page; ?>&filter=" class="btn btn-info">All</a>
    <?php } else { ?>
    <a href="/log/view?page=<?php echo $page; ?>&filter=error" class="btn btn-danger">Errors</a>
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
