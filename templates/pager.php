<ul class="pager">
    <li class="previous<?php echo ($prev?'':' disabled'); ?>">
        <a href="<?php echo ($prev?$url.$prev->id:'#'); ?>">&larr; Older</a>
    </li>
    <li class="next<?php echo ($next?'':' disabled'); ?>">
        <a href="<?php echo ($next?$url.$next->id:'#'); ?>">Newer &rarr;</a>
    </li>
</ul>
