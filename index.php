<?php
require_once("api/api-main.php");
$_current_page = "home";
?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <title><?= $settings['site_name']; ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/u/bs/jq-2.2.3,dt-1.10.12,r-2.1.0/datatables.min.css"/>
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
