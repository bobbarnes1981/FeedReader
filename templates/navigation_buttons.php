<?php foreach ($buttons as $button) { ?>
<?php if (is_array($button['link'])) { ?>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $button['title']; ?> <b class="caret"></b></a>
    <ul class="dropdown-menu">
    <?php foreach ($button['link'] as $button_dropdown) { ?>
        <li class="<?php echo ($button_dropdown['id'] == $active?'active':'') ?>"><a href="<?php echo $button_dropdown['link']; ?>"><?php echo $button_dropdown['title']; ?></a></li>
    <?php } ?>
    </ul>
</li>
<?php } else { ?>
<li class="<?php echo ($button['id'] == $active?'active':'') ?>"><a href="<?php echo $button['link']; ?>"><?php echo $button['title']; ?></a></li>
<?php } ?>
<?php } ?>
