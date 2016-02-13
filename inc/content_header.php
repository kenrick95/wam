<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><?= $settings['site_name']; ?></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
            <li<?php if ($_current_page == "home") { echo " class=\"active\""; } ?>><a href="index.php">Home</a></li>
            <li<?php if ($_current_page == "progress") { echo " class=\"active\""; } ?>><a href="progress.php">Check progress</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
            <li><a href="<?= $settings['talk_page_link'] ?>">Talk page</a></li>
            <li<?php if ($_current_page == "judging") { echo " class=\"active\""; } ?>><a href="judging.php">Judging</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>
