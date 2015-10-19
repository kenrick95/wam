<?php
require_once("api/api-main.php");
$_current_page = "progress";
$username = isset($_GET["username"]) ? $_GET["username"] : "";
$wiki = isset($_GET["wiki"]) ? $_GET["wiki"] : "";
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Wikipedia Asian Month</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
    <?php require_once("inc/content_header.php"); ?>
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
            require_once("inc/progress_individual.php");
        } else {
            $participants = get_participants_list();
            require_once("inc/participant_list.php");
        }
        ?>
    </main>
    <footer class="container">
    <?php require_once("inc/content_footer.php"); ?>
    </footer>
    <script src="//tools-static.wmflabs.org/cdnjs/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//tools-static.wmflabs.org/cdnjs/ajax/libs/twitter-bootstrap/3.1.1/js/bootstrap.min.js"></script>
</body>
</html>
