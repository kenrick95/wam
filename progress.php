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
            $all_pages = get_all_new_pages_of_user($username, $wiki)['query']['usercontribs'];
            $all_pageids = [];
            for ($i = 0; $i < count($all_pages); $i++) {
                array_push($all_pageids, $all_pages[$i]['pageid']);
            }
            $all_page_sizes = get_page_size($all_pageids, $wiki)['query']['pages'];
            //echo json_encode($all_page_sizes);         
            //echo json_encode($all_pages);
            ?>
            <h2>Progress for <a href="//<?= $wiki ?>/wiki/User:<?= $username ?>"><?= $username ?></a> at <a href="//<?= $wiki ?>/wiki/"><?= $wiki; ?></a></h2>
            <div class="table-responsive">
                <table class="table">
                <thead>
                    <tr>
                    <th>Article name</th>
                    <th>Date and time</th>
                    <th>Current byte count</th>
                    <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    for ($i = 0; $i < count($all_pages); $i++) {
                    ?>
                    <tr>
                        <td><a href="//<?= $wiki ?>/wiki/<?= $all_pages[$i]['title'] ?>"><?= $all_pages[$i]['title'] ?></a></td>
                        <td><?= date("j F Y, H:i:s", strtotime($all_pages[$i]['timestamp'])) ?></td>
                        <td><?= $all_page_sizes[$all_pages[$i]['pageid']]['revisions'][0]['size'] ?></td>
                        <td><!-- <?= $all_pages[$i]['pageid'] ?> --><span class="label label-warning">Pending</span></td>
                    </tr>
                    <?php
                    }
                ?>
                </tbody>
                </table>
            </div>
        <?php
        } else {
        $participants = get_participants_list();
        ?>
        <div class="table-responsive">
            <table class="table">
            <thead>
                <tr>
                <th>Username</th>
                <th>Wiki</th>
                <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            <?php
                for ($i = 0; $i < count($participants); $i++) {
                ?>
                <tr>
                    <td><a href="//<?= $participants[$i]['wiki'] ?>/wiki/User:<?= $participants[$i]['username'] ?>"><?= $participants[$i]['username'] ?></a></td>
                    <td><a href="//<?= $participants[$i]['wiki'] ?>/wiki/"><?= $participants[$i]['wiki'] ?></a></td>
                    <td><a class="btn btn-default btn-xs" href="progress.php?username=<?= $participants[$i]['username'] ?>&amp;wiki=<?= $participants[$i]['wiki'] ?>">Check progress</a></td>
                </tr>
                <?php
                }
            ?>
            </tbody>
            </table>
        </div>
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