<?php
require_once("api/api-main.php");
$_current_page = "progress";
$username = isset($_GET["username"]) ? $_GET["username"] : "";
$wiki = isset($_GET["wiki"]) ? $_GET["wiki"] : "";

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Wikipedia Asian Month</title>
    <link href="//tools-static.wmflabs.org/cdnjs/ajax/libs/twitter-bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
    <?php require_once("content_header.php"); ?>
    </header>
    <main class="container">
        <?php
        if (!empty($username) && !empty($wiki)) {
            ?>
        <h2>Progress for User:<?= $username; ?> at <?= $wiki; ?></h2>
        <?php echo json_encode(get_all_new_pages_of_user($username, $wiki)); ?>
        <?php
        } else {
        ?>
        <form class="form-horizontal" method="GET">
        <div class="form-group">
            <label for="username" class="col-sm-2 control-label">Username:</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" name="username" id="username" placeholder="Username (e.g. Jimmy_Wales)">
            </div>
        </div>
        <div class="form-group">
            <label for="wiki" class="col-sm-2 control-label">Wiki:</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" name="wiki" id="wiki" placeholder="Wiki (e.g. id.wikipedia.org)">
            </div>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
        </form>
        <?php
        }
        ?>
    </main>
    <footer class="container">
        
    </footer>
    <script src="//tools-static.wmflabs.org/cdnjs/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//tools-static.wmflabs.org/cdnjs/ajax/libs/twitter-bootstrap/3.1.1/js/bootstrap.min.js"></script>
</body>
</html>