<?php
require_once("api/api-main.php");
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Wikipedia Asian Month: November 2015</title>
    <link href="//tools-static.wmflabs.org/cdnjs/ajax/libs/twitter-bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Wikipedia Asian Month</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="https://meta.wikimedia.org/wiki/Talk:Wikipedia_Asian_Month">Talk page</a></li>
                    <li><a href="#">One more separated link</a></li>
                    </ul>
                    </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Link</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div>
        </nav>
    </header>
    <main class="container">
        <?php echo get_meta_page(); ?>
    </main>
    <footer class="container">

    </footer>
    <script src="//tools-static.wmflabs.org/cdnjs/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//tools-static.wmflabs.org/cdnjs/ajax/libs/twitter-bootstrap/3.1.1/js/bootstrap.min.js"></script>
</body>
</html>