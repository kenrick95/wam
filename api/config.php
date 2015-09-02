<?php
date_default_timezone_set('UTC');

$settings['cookiefile'] = "cookies.tmp";
$settings['period'] = [];
$settings['period']['start'] = mktime(0, 0, 0, 1, 1, 2015); // mktime(0, 0, 0, 11, 1, 2015);
$settings['period']['end'] = mktime(23, 59, 59, 11, 30, 2015);
