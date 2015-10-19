<?php
include "api-main.php";

$func = isset($_GET['func']) ? $_GET['func'] : '';
$x = isset($_GET['x']) ? $_GET['x'] : '';
$y = isset($_GET['y']) ? $_GET['y'] : '';
$permitted_fn = ['get_page_wordcount'];

if (function_exists($func) && in_array($func, $permitted_fn)) {
    echo json_encode(call_user_func($func, $x, $y));
}
