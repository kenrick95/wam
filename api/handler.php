<?php
include "api-main.php";

$func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
$params = $_REQUEST;
array_shift($params);

$permitted_fn = ['purge_wordcount', 'do_judgement', 'get_user_stats'];

if (function_exists($func) && in_array($func, $permitted_fn)) {
    echo json_encode(call_user_func_array($func, $params));
}
