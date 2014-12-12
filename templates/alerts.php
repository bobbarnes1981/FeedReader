<?php foreach (array('danger', 'success', 'info') as $type) { ?>

    <?php if (array_key_exists($type, $alerts)) { ?>

        <?php foreach ($alerts[$type] as $alert) { ?>

            <div class="alert alert-<?php echo $type;?>"><?php echo $alert; ?></div>

        <?php } ?>

    <?php } ?>

<?php } ?>
