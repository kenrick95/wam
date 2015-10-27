<?php
require_once("api/api-main.php");
if (empty($settings['gTokenKey'])) {
    doAuthorizationRedirect();
}

// TODO fetch list the organizers
// TODO match current OAuth-ed user with that list


$_current_page = "judging";
$pageid = isset($_GET["pageid"]) ? $_GET["pageid"] : "";
$username = isset($_GET["username"]) ? $_GET["username"] : "";
$wiki = isset($_GET["wiki"]) ? $_GET["wiki"] : "";
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Wikipedia Asian Month - Judging<?php
        if (!empty($pageid) && !empty($username) && !empty($wiki)) {
            $result = get_page_size([$pageid], $wiki)['query']['pages'];
            $article_title = $result[$pageid]['title'];
            echo ": $article_title by $username at $wiki";
        } else if (!empty($username) && !empty($wiki)) {
            echo " articles by $username at $wiki";
        } else {
            if (!empty($filter)) {
                echo " articles at $filter";
            }
        }
        ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
    <?php require_once("inc/content_header.php"); ?>
    </header>
    <main class="container">
        <?php
        if (!empty($pageid) && !empty($username) && !empty($wiki)) {

            $article_page_size = $result[$pageid]['revisions'][0]['size'];
            $article_word_count = get_page_wordcount($pageid, $wiki);

            $verdicts = get_verdict($username, $wiki);
            $verdict = isset($verdicts[$article_title]) ? $verdicts[$article_title]['verdict'] : "";

            require_once("inc/judge_individual_article.php");
        } else if (!empty($username) && !empty($wiki)) {
            $all_pages = get_all_new_pages_of_user($username, $wiki)['query']['usercontribs'];
            $all_pageids = [];
            $all_verdicts = get_verdict($username, $wiki);

            for ($i = 0; $i < count($all_pages); $i++) {
                array_push($all_pageids, $all_pages[$i]['pageid']);
            }
            $all_page_sizes = get_page_size($all_pageids, $wiki)['query']['pages'];



            //echo json_encode($all_page_sizes);
            //echo json_encode($all_pages);
            require_once("inc/judge_individual_list.php");
        } else {
            $participants = get_participants_list();
            if (!empty($filter)) {
                require_once("inc/judge_participant_list.php");
            } else {
                require_once("inc/judge_wiki_list.php");
            }
        }
        ?>
    </main>
    <footer class="container">
    <?php require_once("inc/content_footer.php"); ?>
    </footer>
</body>
</html>
