<?php
require_once("config.php");
function http_request($url, $post = "") {
    global $settings;
    //prof_flag("CURL: " . $url . " -- " .$post);

    $ch = curl_init();
    //Change the user agent below suitably
    curl_setopt($ch, CURLOPT_USERAGENT, $settings['gUserAgent']);
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

function within_period ($time) {
    global $settings;
    return $settings['period']['start'] <= $time && $time <= $settings['period']['end'];
}

// http://stackoverflow.com/a/29022400/917957
// // Call this at each point of interest, passing a descriptive string
// function prof_flag($str)
// {
//     global $prof_timing, $prof_names;
//     $prof_timing[] = microtime(true);
//     $prof_names[] = $str;
// }
//
// // Call this when you're done and want to see the results
// function prof_print()
// {
//     global $prof_timing, $prof_names;
//     $size = count($prof_timing);
//     for($i=0;$i<$size - 1; $i++)
//     {
//         echo "<b>{$prof_names[$i]}</b><br>";
//         echo sprintf("&nbsp;&nbsp;&nbsp;%f<br>", $prof_timing[$i+1]-$prof_timing[$i]);
//     }
//     echo "<b>{$prof_names[$size-1]}</b><br>";
// }

// http://stackoverflow.com/a/18499265/917957
function utf8_char_code_at($str, $index)
{
    $char = mb_substr($str, $index, 1, 'UTF-8');

    if (mb_check_encoding($char, 'UTF-8')) {
        $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
        return hexdec(bin2hex($ret));
    } else {
        return null;
    }
}
