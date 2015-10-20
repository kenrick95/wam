<?php
date_default_timezone_set('UTC');

$settings['cookiefile'] = "cookies.tmp";
$settings['period'] = [];
$settings['period']['start'] = mktime(0, 0, 0, 10, 1, 2015); // mktime(0, 0, 0, 11, 1, 2015);
$settings['period']['end'] = mktime(23, 59, 59, 11, 30, 2015);

$settings['mwOAuthAuthorizeUrl'] = 'https://www.mediawiki.org/wiki/Special:OAuth/authorize';
$settings['mwOAuthUrl'] = 'https://www.mediawiki.org/w/index.php?title=Special:OAuth';
$settings['mwOAuthIW'] = 'mw';
$settings['apiUrl'] = 'https://test.wikipedia.org/w/api.php';
$settings['mytalkUrl'] = 'https://test.wikipedia.org/wiki/Special:MyTalk#Hello.2C_world';
$settings['errorCode'] = 200;

# REMOVE BEFORE COMMIT!
$settings['gUserAgent'] = "Kenrick-Tool/1.0 wam";
$settings['gConsumerKey'] = "";
$settings['gConsumerSecret'] = "";

$settings['gTokenKey'] = '';
$settings['gTokenSecret'] = '';
session_start();
if ( isset( $_SESSION['tokenKey'] ) ) {
    $settings['gTokenKey'] = $_SESSION['tokenKey'];
    $settings['gTokenSecret'] = $_SESSION['tokenSecret'];
}
session_write_close();
if ( isset( $_GET['oauth_verifier'] ) && $_GET['oauth_verifier'] ) {
    fetchAccessToken();
}
