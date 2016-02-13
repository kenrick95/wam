<?php
require_once("api/api-main.php");
$_current_page = "home";
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $settings['site_name']; ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
    <?php require_once("inc/content_header.php"); ?>
    </header>
    <main class="container">
        <?php echo get_main_page(); ?>
    </main>
    <footer class="container">
    <?php require_once("inc/content_footer.php"); ?>
    </footer>
</body>
</html>
