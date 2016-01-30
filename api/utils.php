<?php
require_once("config.php");
function http_request($url, $post = "", $header = "", &$ch = null) {
    global $settings;
    //prof_flag("CURL: " . $url . " -- " .$post);
    if ( !$ch ) {
      $ch = curl_init();
    }

    //Change the user agent below suitably
    if (!empty($post)) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, array( $header ) );
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt($ch, CURLOPT_USERAGENT, $settings['gUserAgent']);
    curl_setopt($ch, CURLOPT_URL, ($url));
    curl_setopt($ch, CURLOPT_ENCODING, "UTF-8" );
    curl_setopt($ch, CURLOPT_HEADER, 0 );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $settings['cookiefile']);
    curl_setopt($ch, CURLOPT_COOKIEJAR,  $settings['cookiefile']);
    if (!empty($post)) {
      curl_setopt($ch, CURLOPT_POST, true );
      if (is_array($post)) {
          $post = http_build_query($post);
      }
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
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
function within_period ($time) {
    global $settings;
    return $settings['period']['start'] <= $time && $time <= $settings['period']['end'];
}

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
