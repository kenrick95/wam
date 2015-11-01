<?php
date_default_timezone_set('UTC');

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

session_start();
if ( isset( $_SESSION['tokenKey'] ) ) {
    $settings['gTokenKey'] = $_SESSION['tokenKey'];
    $settings['gTokenSecret'] = $_SESSION['tokenSecret'];
    if ( isset( $_SESSION['loggedinUsername'] ) ) {
        $settings['loggedinUsername'] = $_SESSION['loggedinUsername'];
    }

}
session_write_close();
if ( isset( $_GET['oauth_verifier'] ) && $_GET['oauth_verifier'] ) {
    fetchAccessToken();
}
