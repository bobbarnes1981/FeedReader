<h2>User</h2>
<div class="well">
    <a href="/user/create" class="btn btn-success">Create</a>
    <?php echo $pagination; ?>
</div>
<div class="list-group">
<?php foreach ($users as $user) { ?>
    <div class="list-group-item">
        <?php if (false) { ?>
            <span class="glyphicon glyphicon-star"></span>
        <?php } else { ?>
            <span class="glyphicon"></span>
        <?php } ?>
        <a href="/user/edit?id=<?php echo $user->id; ?>" class="-btn -btn-default -btn-xs">
            <?php echo $user->username;?>
        </a>
        <?php foreach ($user->GetRoles() as $role) { ?>
        <span class="label label-default"><?php echo $role->name; ?></span>
        <?php } ?>
        <a class="btn btn-danger btn-xs pull-right" href="/user/delete?id=<?php echo $user->id; ?>">Delete</a>
    </div>
<?php } ?>
</div>
