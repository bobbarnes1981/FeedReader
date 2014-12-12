<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php echo ucfirst($title); ?></title>
        <link href="/media/css/bootstrap.css" rel="stylesheet">
        <link href="/media/css/bootstrap-glyphicons.css" rel="stylesheet">
        <link href="/media/css/style.css" rel="stylesheet">
        <script src="/media/js/jquery-2.0.3.js"></script>
        <script src="/media/js/bootstrap.js"></script>
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
