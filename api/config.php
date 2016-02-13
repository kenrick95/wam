<?php
date_default_timezone_set('UTC');
header("Content-Type: text/html; charset=UTF-8");
mb_internal_encoding("UTF-8");

$settings['site_name'] = "Wikipedia Asian Month";
$settings['main_page_link'] = "https://meta.wikimedia.org/wiki/Wikipedia_Asian_Month";
$settings['talk_page_link'] = "https://meta.wikimedia.org/wiki/Talk:Wikipedia_Asian_Month";
$settings['main_page_title'] = "Wikipedia_Asian_Month/2015_Edition";
$settings['main_page_wiki'] = "meta.wikimedia.org";
$settings['participant_list_page_id'] = 9086071; // [[meta:Wikipedia_Asian_Month/Participants]]
$settings['organizer_list_page_id'] = 8962039; // [[meta:Wikipedia_Asian_Month/Organizers]]
$settings['judge_page_pattern'] = "Wikipedia Asian Month/Judging/[wiki]/[username]";
$settings['judge_verdict_pattern'] = "/\* {{WAM\-art \| title = (.+) \| verdict = ([^|]*) \| last_updated_by = ([^|]*)( \| remarks = (.*) )?}}/";
$settings['judge_verdict_wikitext_pattern'] = "* {{WAM-art | title = [page_title] | verdict = [verdict] | last_updated_by = [current_username] | remarks = [remarks] }}";

$settings['judge_edit_summary_pattern'] = '[[Wikipedia Asian Month|WAM]]: Give [verdict] verdict on article [page_title] for user [username] of [wiki]';

$settings['cookiefile'] = "cookies.tmp";
$settings['period'] = [];
$settings['period']['start'] = mktime(0, 0, 0, 11, 1, 2015);
$settings['period']['end'] = mktime(23, 59, 59, 11, 30, 2015);

$settings['mwOAuthAuthorizeUrl'] = 'https://www.mediawiki.org/wiki/Special:OAuth/authorize';
$settings['mwOAuthUrl'] = 'https://www.mediawiki.org/w/index.php?title=Special:OAuth';
$settings['mwOAuthIW'] = 'mw';
$settings['apiUrl'] = 'https://meta.wikimedia.org/w/api.php';
$settings['errorCode'] = 200;

$settings['gUserAgent'] = "";
$settings['gConsumerKey'] = "";
$settings['gConsumerSecret'] = "";
include("secret_config.php");

$settings['gTokenKey'] = '';
$settings['gTokenSecret'] = '';
$settings['loggedinUsername']  = '';

$settings['minimumArticlePageSize'] = array(
    "az.wikipedia.org" => 3000,
    "uz.wikipedia.org" => 2500,
    "pa.wikipedia.org" => 2500,
    "ml.wikipedia.org" => 2500,
    "*" => 3500,
);
$settings['minimumWordCount'] = array(
    "az.wikipedia.org" => 250,
    "uz.wikipedia.org" => 300,
    "pa.wikipedia.org" => 200,
    "ml.wikipedia.org" => 20,
    "*" => 300,
);

$settings['CJK_wikis'] = ["zh.wikipedia.org", "ja.wikipedia.org",
    "ko.wikipedia.org", "zh-yue.wikipedia.org"];


session_start();
if ( isset( $_SESSION['tokenKey'] ) ) {
    $settings['gTokenKey'] = $_SESSION['tokenKey'];
    $settings['gTokenSecret'] = $_SESSION['tokenSecret'];
    if ( isset( $_SESSION['loggedinUsername'] ) ) {
        $settings['loggedinUsername'] = $_SESSION['loggedinUsername'];
    }

}
session_write_close();
