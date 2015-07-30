<?php
require_once("config.php");
function http_request($url, $post = "") {
    global $settings;

    $ch = curl_init();
    //Change the user agent below suitably
    curl_setopt($ch, CURLOPT_USERAGENT, 'Kenrick-Tool/wam');
    curl_setopt($ch, CURLOPT_URL, ($url));
    curl_setopt($ch, CURLOPT_ENCODING, "UTF-8" );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $settings['cookiefile']);
    curl_setopt($ch, CURLOPT_COOKIEJAR,  $settings['cookiefile']);
    if (!empty($post)) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    // UNCOMMENT TO DEBUG TO output.tmp
    // curl_setopt($ch, CURLOPT_VERBOSE, true); // Display communication with server
    // $fp = fopen("output.tmp", "w");
    // curl_setopt($ch, CURLOPT_STDERR, $fp); // Display communication with server
    
    $return = curl_exec($ch);
    if (!$return) {
        throw new Exception("Error getting data from server ($url): " . curl_error($ch));
    }

    curl_close($ch);
    
    return $return;
}
function array_to_string($array, $join_elem, $join_kv) {
    $temp_arr = [];
    foreach ($array as $key => $value) {
        array_push($temp_arr, $key . $join_kv . $value);
    }
    return implode($join_elem, $temp_arr);
}