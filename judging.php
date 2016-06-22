<?php
require_once("api/api-main.php");
if ( isset( $_GET['oauth_verifier'] ) && $_GET['oauth_verifier'] ) {
    fetch_oauth_access_token();
}

if (isset($_GET['retry'])) {
  session_start();
  $settings['gTokenKey'] = '';
  $settings['gTokenSecret'] = '';
  $settings['loggedinUsername'] = '';
  unset($_SESSION['tokenKey']);
  unset($_SESSION['tokenSecret']);
  unset($_SESSION['loggedinUsername']);
  session_write_close();
}
if (empty($settings['gTokenKey'])) {
  oauth_auth_redirect();
}

// fetch list the organizers
$organizers = get_organizers_list();



if (empty($settings['loggedinUsername'])){
    $settings['loggedinUsername'] = fetch_current_username();
}
// match current OAuth-ed user with that list
$okay = in_array(str_replace(" ", "_", trim($settings['loggedinUsername'])), $organizers);


$_current_page = "judging";
$pageid = isset($_GET["pageid"]) ? $_GET["pageid"] : "";
$username = isset($_GET["username"]) ? $_GET["username"] : "";
$wiki = isset($_GET["wiki"]) ? $_GET["wiki"] : "";
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <title><?= $settings['site_name']; ?> - Judging<?php
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/u/bs/jq-2.2.3,dt-1.10.12,r-2.1.0/datatables.min.css"/>
    <link rel="stylesheet" href="css/style.css">
 
</head>
<body>
    <header>
    <?php require_once("inc/content_header.php"); ?>
    </header>
    <main class="container">
        <?php
        if (!$okay) {
            ?>
            <div class="alert alert-danger" role="alert">
                You can't access this section because you are not the organizer in any wiki. Probably you want to check <a href="progress.php">your progress</a>.<hr>
                If you think this is an error, try <a href="judging.php?retry=1">reauthorize this tool</a>. If it's still not working, please <a href="https://meta.wikimedia.org/wiki/Special:EmailUser/Kenrick95">contact Kenrick</a>.
            </div>
            <div class="well">
            <b>Debug:</b><br>
            Current username: <?= $settings['loggedinUsername'] ?><br>
            Organizer List:
            <?php
            print_r($organizers);
            ?>
            </div>
            <?php
        } else if (!empty($pageid) && !empty($username) && !empty($wiki)) {

            $article_page_size = $result[$pageid]['revisions'][0]['size'];
            $article_word_count = get_page_wordcount($pageid, $wiki);

            $verdicts = get_verdict($username, $wiki);
            $verdict = isset($verdicts[$article_title]) ? $verdicts[$article_title]['verdict'] : "";
            $remarks = isset($verdicts[$article_title]) ? $verdicts[$article_title]['remarks'] : "";

            require_once("inc/judge_individual_article.php");
        } else if (!empty($username) && !empty($wiki)) {
            $all_pages = get_all_new_pages_of_user($username, $wiki)['query']['usercontribs'];
            $all_pageids = [];
            $all_verdicts = get_verdict($username, $wiki);

            for ($i = 0; $i < count($all_pages); $i++) {
                array_push($all_pageids, $all_pages[$i]['pageid']);
            }
            $all_page_sizes = get_page_size($all_pageids, $wiki)['query']['pages'];
            $all_wordcounts = get_pages_wordcount($all_pageids, $wiki);

            //echo json_encode($all_page_sizes);
            //echo json_encode($all_pages);
            require_once("inc/judge_individual_list.php");
        } else {
            $participants = get_participants_list();
            if (!empty($filter)) {
                if ($filter == 'all') {
                  require_once("inc/judge_all_participant_list.php");
                } else {
                  require_once("inc/judge_participant_list.php");
                }

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
