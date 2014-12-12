<?php $width = (isset($width)?$width/2:3); ?>
<ul class="pagination pull-right" style="margin: 0px 10px">
    <li class="<?php echo ($page<=1?'disabled':''); ?>">
        <a href="<?php echo $url.($page-1); ?>">&laquo;</a>
    </li>
    <?php for ($i = 1; $i <= $max; $i++) { ?>
        <?php if ($i <= $width || $i > $max - $width) { ?>
    <li class="<?php echo ($page==$i?'active':''); ?>">
        <a href="<?php echo $url.$i; ?>"><?php echo $i; ?></a>
    </li>
        <?php } else if ($i == $width + 1) { ?>
    <li class="disabled">
        <a href="">...</a>
    </li>
        <?php } ?>
    <?php } ?>
    <li class="<?php echo ($page>=$max?'disabled':''); ?>">
        <a href="<?php echo $url.($page+1); ?>">&raquo;</a>
    </li>
</ul>
