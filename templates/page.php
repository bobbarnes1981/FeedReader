<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php echo ucfirst($title); ?></title>
		<?php echo Html::style('media/css/bootstrap.css'); ?>
        <?php echo Html::style('media/css/bootstrap-glyphicons.css'); ?>
        <?php echo Html::style('media/css/style.css'); ?>
        <?php echo Html::script('media/js/jquery-2.0.3.js'); ?>
        <?php echo Html::script('media/js/bootstrap.js'); ?>
    </head>
    <body>
        <?php echo $navigation; ?>
        <div id="wrapper">
            <div id="content" class="container">
                <div class="navigation-padding">
                    <?php echo $alerts; ?>
                    <?php echo $content; ?>
                </div>
            </div>
            <div id="footer" class="text-center">
                <span class="text-muted credit">FeedReader<br/>Robert Barnes &copy; 2013</span>
            </div>
        </div>
    </body>
</html>
