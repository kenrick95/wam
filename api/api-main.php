<?php
require_once("config.php");
require_once("utils.php");
function api_query ($params, $wiki = "meta.wikimedia.org") {
    if (is_array($params)) {
        $params = array_to_string($params, "&", "=");
    }

    global $settings;
    $url = "http://" . $wiki . "/w/api.php?action=query&format=json";
    $data = http_request($url, $params);
    if (empty($data)) {
        throw new Exception("No data received from server. Check that API is enabled.");
    }
    return $data;
}
function get_new_pages_of_user ($user = "", $wiki = "meta.wikimedia.org", $limit = 500, $uccontinue) {
    if (empty($limit)) {
        $limit = 500;
    }
    global $settings;

    $params = array(
        "action" => "query",
        "list" => "usercontribs",
        "format" => "json",
        "uclimit" => $limit,
        "ucuser" => $user,
        "ucnamespace" => "0",
        "ucprop" => "ids|title|timestamp|flags",
        "ucshow" => "new",
        "continue" => "",
        "ucdir" => "newer",
        "ucstart" => $settings['period']['start'],
        "ucend" => $settings['period']['end']
        );
    if (!empty($uccontinue)) {
        $params['uccontinue'] = $uccontinue;
    }
    // echo json_encode($params) . "\n\n";
    return json_decode(api_query($params, $wiki), true);
}
/**
 * Get content of a page
 * @param  array  $pageids Array of page IDs
 * @return [type]         [description]
 */
function get_page_content ($pageids = [], $wiki = "meta.wikimedia.org") {
    $params = array(
        "action" => "query",
        "prop" => "revisions",
        "format" => "json",
        "rvprop" => "content",
        "pageids" => implode("|", $pageids)
        );
    return json_decode(api_query($params, $wiki), true);
}

/**
 * Get content of a page
 * @param  string  $page_title
 * @return [type]         [description]
 */
function get_page_content_using_title ($page_title, $wiki = "meta.wikimedia.org") {
    $params = array(
        "action" => "query",
        "prop" => "revisions",
        "format" => "json",
        "rvprop" => "content",
        "titles" => $page_title
        );
    return json_decode(api_query($params, $wiki), true);
}

/**
 * Get page title from page id
 * @param  integer  $pageid page ID
 * @return string   page title
 */
function get_page_title ($pageid, $wiki = "meta.wikimedia.org") {
    $params = array(
        "action" => "query",
        "prop" => "info",
        "format" => "json",
        "pageids" => $pageid
        );
    return json_decode(api_query($params, $wiki), true)['query']['pages'][$pageid]['title'];
}
/**
 * Get page id from page title
 * @param  integer  $pageid page ID
 * @return string   page title
 */
function get_page_id ($title, $wiki = "meta.wikimedia.org") {
    $params = array(
        "action" => "query",
        "prop" => "info",
        "format" => "json",
        "titles" => $title
        );
    return key(json_decode(api_query($params, $wiki), true)['query']['pages']);
}

/**
 * Get plain text of a page
 * @param  array  $pageids Array of page IDs
 * @return [type]         [description]
 */
function get_page_text_content ($pageids = [], $wiki = "meta.wikimedia.org") {
    $params = array(
        "action" => "query",
        "prop" => "extracts",
        "format" => "json",
        "explaintext" => "",
        "pageids" => implode("|", $pageids)
        );
    return json_decode(api_query($params, $wiki), true);
}

function get_page_wordcount ($pageid, $wiki = "meta.wikimedia.org") {
    global $settings;
    $text = get_page_text_content([$pageid], $wiki)['query']['pages'][$pageid]['extract'];
    $enc = mb_detect_encoding($text, "UTF-8,ISO-8859-1");
    $text = iconv($enc, "UTF-8", $text);

    $cnt = 0;
    if (in_array($wiki, $settings['CJK_wikis'])) {
        $regex = '/[a-zA-Z0-9_\x{0392}-\x{03c9}\x{00c0}-\x{00ff}\x{0600}-\x{06ff}]+|[\x{4e00}-\x{9fff}\x{3400}-\x{4dbf}\x{f900}-\x{faff}\x{3040}-\x{309f}\x{ac00}-\x{d7af}]+/u';

        preg_match_all($regex, $text, $m);
        $m = $m[0];
        $cnt = 0;
        for ($i = 0; $i < count($m); $i++) {
            if (utf8_char_code_at($m[$i], 0) >= 0x4e00) {
             $cnt += mb_strlen($m[$i]);
           } else {
             $cnt += 1;
           }
        }
    } else {
        $regex = '/[\p{P}\p{Z}\p{S}\p{C}]+/u';
        $cnt = count(preg_split($regex, $text));
    }

    return $cnt;
}

/**
 * Get page size of a page
 * @param  array  $pageids Array of page IDs
 * @return [type]         [description]
 */
function get_page_size ($pageids = [], $wiki = "meta.wikimedia.org") {
    $data = null;
    $cnt = 0;
    while ($cnt * 50 < count($pageids)) {
        $temp_pageids = array_slice($pageids, $cnt * 50, 50);

        $cnt++;
        $params = array(
            "action" => "query",
            "prop"   => "revisions",
            "format" => "json",
            "rvprop" => "size|timestamp",
            "pageids" => implode("|", $temp_pageids)
            );
        $temp_data = json_decode(api_query($params, $wiki), true);
        if ($data === null)
            $data = $temp_data;
        else {
            foreach ($temp_data['query']['pages'] as $key => $value) {
                $data['query']['pages'][$key] = $temp_data['query']['pages'][$key];
            }
        }
    }

    return $data;
}/**
 * Get page size of a page using page titles
 * @param  array  $titles Array of page titles
 * @return [type]         [description]
 */
function get_page_size_using_title ($titles = [], $wiki = "meta.wikimedia.org") {
    $data = null;
    $cnt = 0;
    while ($cnt * 50 < count($titles)) {
        $temp_pagetitles = array_slice($titles, $cnt * 50, 50);

        $cnt++;
        $params = array(
            "action" => "query",
            "prop"   => "revisions",
            "format" => "json",
            "rvprop" => "size|timestamp",
            "titles" => implode("|", $temp_pagetitles)
            );
        $temp_data = json_decode(api_query($params, $wiki), true);
        if ($data === null)
            $data = $temp_data;
        else {
            foreach ($temp_data['query']['pages'] as $key => $value) {
                $data['query']['pages'][$key] = $temp_data['query']['pages'][$key];
            }
        }
    }

    return $data;
}

function get_meta_page () {
    return get_page_content_html("Wikipedia_Asian_Month", "meta.wikimedia.org");
}
function get_page_content_html ($title, $wiki = "meta.wikimedia.org") {
    $title = rawurlencode($title);
    $url = "http://$wiki/api/rest_v1/page/html/$title";
    $params = "";
    $raw_data = http_request($url, $params);
    if (empty($raw_data)) {
        throw new Exception("No data received from server. Check that API is enabled.");
    }
    $stylesheet = explode("<link rel=\"stylesheet\" href=\"", $raw_data)[1];
    $stylesheet = explode("\"/>", $stylesheet)[0];

    preg_match_all("/<body[^>]*>(.*?)<\/body>/is", $raw_data, $data);
    $data = $data[1][0];
    $data = str_replace("./", "//$wiki/wiki/", $data);

    //$ret = "<link rel=\"stylesheet\" href=\"" . $stylesheet . "\"/>"
    //    . $data;
    $ret = $data;
    return $ret;
}

function get_all_new_pages_of_user ($user = "", $wiki = "meta.wikimedia.org") {
    $data = null;
    $temp_data = null;
    $uccontinue = "";
    $cnt = 0;
    while ($data === null /*|| $cnt < 10){// */|| isset($temp_data['continue'])) {
        $temp_data = get_new_pages_of_user($user, $wiki, 500, $uccontinue);
        $uccontinue = !empty($temp_data['continue']['uccontinue']) ? $temp_data['continue']['uccontinue'] : "";
        if ($data === null)
            $data = $temp_data;
        else {
            for ($i = 0; $i < count($temp_data['query']['usercontribs']); $i++) {
                array_push($data['query']['usercontribs'], $temp_data['query']['usercontribs'][$i]);
            }
        }
        $cnt++;
    }

    return $data;
}


function get_participants_list() {
    // [[meta:Wikipedia_Asian_Month/Participants]]
    $raw_data = get_page_content([9086071])['query']['pages'][9086071]['revisions'][0]['*'];

    preg_match_all("/{{target\s*\|\s*user\s*=\s*(.+)\s*\|\s*site\s*=\s*(.+)\s*}}/", $raw_data, $data);
    //var_dump($data);
    $ret = [];
    for ($i = 1; $i < count($data[2]); $i++) {
        array_push($ret, array(
            "username" => trim($data[1][$i]),
            "wiki" =>trim($data[2][$i])
        ));
    }
    //var_dump($ret);
    return $ret;
}


function get_organizers_list() {
    // [[meta:Wikipedia_Asian_Month/Organizers]]
    $raw_data = get_page_content([8962039])['query']['pages'][8962039]['revisions'][0]['*'];

    preg_match_all("/{{target\s*\|\s*user\s*=\s*(.+)\s*\|\s*site\s*=\s*(.+)\s*}}/", $raw_data, $data);
    //var_dump($data);
    $ret = [];
    for ($i = 1; $i < count($data[2]); $i++) {
        array_push($ret, str_replace(" ", "_", trim($data[1][$i])));
    }
    //var_dump($ret);
    return $ret;
}




/**
 * Utility function to sign a request
 *
 * Note this doesn't properly handle the case where a parameter is set both in
 * the query string in $url and in $params, or non-scalar values in $params.
 *
 * @param string $method Generally "GET" or "POST"
 * @param string $url URL string
 * @param array $params Extra parameters for the Authorization header or post
 *  data (if application/x-www-form-urlencoded).
 *Â @return string Signature
 */
function sign_request( $method, $url, $params = array() ) {
    global $settings;

    $parts = parse_url( $url );

    // We need to normalize the endpoint URL
    $scheme = isset( $parts['scheme'] ) ? $parts['scheme'] : 'http';
    $host = isset( $parts['host'] ) ? $parts['host'] : '';
    $port = isset( $parts['port'] ) ? $parts['port'] : ( $scheme == 'https' ? '443' : '80' );
    $path = isset( $parts['path'] ) ? $parts['path'] : '';
    if ( ( $scheme == 'https' && $port != '443' ) ||
        ( $scheme == 'http' && $port != '80' )
    ) {
        // Only include the port if it's not the default
        $host = "$host:$port";
    }

    // Also the parameters
    $pairs = array();
    parse_str( isset( $parts['query'] ) ? $parts['query'] : '', $query );
    $query += $params;
    unset( $query['oauth_signature'] );
    if ( $query ) {
        $query = array_combine(
            // rawurlencode follows RFC 3986 since PHP 5.3
            array_map( 'rawurlencode', array_keys( $query ) ),
            array_map( 'rawurlencode', array_values( $query ) )
        );
        ksort( $query, SORT_STRING );
        foreach ( $query as $k => $v ) {
            $pairs[] = "$k=$v";
        }
    }

    $toSign = rawurlencode( strtoupper( $method ) ) . '&' .
        rawurlencode( "$scheme://$host$path" ) . '&' .
        rawurlencode( join( '&', $pairs ) );
    $key = rawurlencode( $settings['gConsumerSecret'] ) . '&' . rawurlencode( $settings['gTokenSecret'] );
    return base64_encode( hash_hmac( 'sha1', $toSign, $key, true ) );
}

/**
 * Request authorization
 * @return void
 */
function doAuthorizationRedirect() {
    global $settings;

    // First, we need to fetch a request token.
    // The request is signed with an empty token secret and no token key.
    $settings['gTokenSecret'] = '';
    $url = $settings['mwOAuthUrl'] . '/initiate';
    $url .= strpos( $url, '?' ) ? '&' : '?';
    $url .= http_build_query( array(
        'format' => 'json',

        // OAuth information
        'oauth_callback' => 'oob', // Must be "oob" for MWOAuth
        'oauth_consumer_key' => $settings['gConsumerKey'],
        'oauth_version' => '1.0',
        'oauth_nonce' => md5( microtime() . mt_rand() ),
        'oauth_timestamp' => time(),

        // We're using secret key signatures here.
        'oauth_signature_method' => 'HMAC-SHA1',
    ) );
    $signature = sign_request( 'GET', $url );
    $url .= "&oauth_signature=" . urlencode( $signature );
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_USERAGENT, $settings['gUserAgent'] );
    curl_setopt( $ch, CURLOPT_HEADER, 0 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    $data = curl_exec( $ch );
    if ( !$data ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Curl error: ' . htmlspecialchars( curl_error( $ch ) );
        exit(0);
    }
    curl_close( $ch );
    $token = json_decode( $data );
    if ( is_object( $token ) && isset( $token->error ) ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Error retrieving token: ' . htmlspecialchars( $token->error );
        exit(0);
    }
    if ( !is_object( $token ) || !isset( $token->key ) || !isset( $token->secret ) ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Invalid response from token request';
        exit(0);
    }

    // Now we have the request token, we need to save it for later.
    session_start();
    $_SESSION['tokenKey'] = $token->key;
    $_SESSION['tokenSecret'] = $token->secret;
    session_write_close();

    // Then we send the user off to authorize
    $url = $settings['mwOAuthAuthorizeUrl'];
    $url .= strpos( $url, '?' ) ? '&' : '?';
    $url .= http_build_query( array(
        'oauth_token' => $token->key,
        'oauth_consumer_key' => $settings['gConsumerKey'],
    ) );
    header( "Location: $url" );
    echo 'Please see <a href="' . htmlspecialchars( $url ) . '">' . htmlspecialchars( $url ) . '</a>';
}

/**
 * Handle a callback to fetch the access token
 * @return void
 */
function fetchAccessToken() {
    global $settings;

    $url = $settings['mwOAuthUrl'] . '/token';
    $url .= strpos( $url, '?' ) ? '&' : '?';
    $url .= http_build_query( array(
        'format' => 'json',
        'oauth_verifier' => $_GET['oauth_verifier'],

        // OAuth information
        'oauth_consumer_key' => $settings['gConsumerKey'],
        'oauth_token' => $settings['gTokenKey'],
        'oauth_version' => '1.0',
        'oauth_nonce' => md5( microtime() . mt_rand() ),
        'oauth_timestamp' => time(),

        // We're using secret key signatures here.
        'oauth_signature_method' => 'HMAC-SHA1',
    ) );
    $signature = sign_request( 'GET', $url );
    $url .= "&oauth_signature=" . urlencode( $signature );
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_USERAGENT, $settings['gUserAgent'] );
    curl_setopt( $ch, CURLOPT_HEADER, 0 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    $data = curl_exec( $ch );
    if ( !$data ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Curl error: ' . htmlspecialchars( curl_error( $ch ) );
        exit(0);
    }
    $token = json_decode( $data );
    if ( is_object( $token ) && isset( $token->error ) ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Error retrieving token: ' . htmlspecialchars( $token->error );
        exit(0);
    }
    if ( !is_object( $token ) || !isset( $token->key ) || !isset( $token->secret ) ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Invalid response from token request';
        exit(0);
    }

    curl_close( $ch );

    // Save the access token
    session_start();
    $_SESSION['tokenKey'] = $settings['gTokenKey'] = $token->key;
    $_SESSION['tokenSecret'] = $settings['gTokenSecret'] = $token->secret;
    session_write_close();
}

function fetch_current_username() {
    // Fetch the username
    $ch = null;
    $res = doApiQuery( array(
        'format' => 'json',
        'action' => 'query',
        'meta' => 'userinfo',
    ), $ch );

    if ( isset( $res->error->code ) && $res->error->code === 'mwoauth-invalid-authorization' ) {
        // We're not authorized!
        echo 'You haven\'t authorized this application yet!';
        echo '<hr>';
        return;
    }

    if ( !isset( $res->query->userinfo ) ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Bad API response: <pre>' . htmlspecialchars( var_export( $res, 1 ) ) . '</pre>';
        exit(0);
    }
    if ( isset( $res->query->userinfo->anon ) ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Not logged in. (How did that happen?)';
        exit(0);
    }
    $current_username = str_replace(" ", "_", trim($res->query->userinfo->name));
    session_start();
    $_SESSION['loggedinUsername'] = $settings['loggedinUsername'] = $current_username;
    session_write_close();
    return $current_username;
}

/**
 * Send an API query with OAuth authorization
 *
 * @param array $post Post data
 * @param object $ch Curl handle
 * @return array API results
 */
function doApiQuery( $post, &$ch = null ) {
    global $settings;

    $headerArr = array(
        // OAuth information
        'oauth_consumer_key' => $settings['gConsumerKey'],
        'oauth_token' => $settings['gTokenKey'],
        'oauth_version' => '1.0',
        'oauth_nonce' => md5( microtime() . mt_rand() ),
        'oauth_timestamp' => time(),

        // We're using secret key signatures here.
        'oauth_signature_method' => 'HMAC-SHA1',
    );
    $signature = sign_request( 'POST', $settings['apiUrl'], $post + $headerArr );
    $headerArr['oauth_signature'] = $signature;

    $header = array();
    foreach ( $headerArr as $k => $v ) {
        $header[] = rawurlencode( $k ) . '="' . rawurlencode( $v ) . '"';
    }
    $header = 'Authorization: OAuth ' . join( ', ', $header );

    if ( !$ch ) {
        $ch = curl_init();
    }
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_URL, $settings['apiUrl'] );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $post ) );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( $header ) );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_USERAGENT, $settings['gUserAgent'] );
    curl_setopt( $ch, CURLOPT_HEADER, 0 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    $data = curl_exec( $ch );
    if ( !$data ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Curl error: ' . htmlspecialchars( curl_error( $ch ) );
        exit(0);
    }
    $ret = json_decode( $data );
    if ( $ret === null ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Unparsable API response: <pre>' . htmlspecialchars( $data ) . '</pre>';
        exit(0);
    }
    return $ret;
}

function get_judged_articles($username, $wiki) {
    $judge_page = 'Wikipedia Asian Month/Judging/' . $wiki . "/" . $username;
    $res = get_page_content_using_title($judge_page)['query']['pages'];
    $judge_page_id = key($res);
    $page_content = $res[$judge_page_id]['revisions'][0]['*'];

    $data = [];
    $regex = "/\* {{WAM\-art \| title = (.+) \| verdict = ([^|]*) \| last_updated_by = ([^|]*)( \| remarks = (.*) )?}}/";
    preg_match_all($regex, $page_content, $data);
    $article_judged = $data[1];

    return array($article_judged, $page_content);
}
function get_verdict($username, $wiki) {
    $judge_page = 'Wikipedia Asian Month/Judging/' . $wiki . "/" . $username;
    $res = get_page_content_using_title($judge_page)['query']['pages'];
    $judge_page_id = key($res);
    if ($judge_page_id < 0)
        return false;
    $page_content = $res[$judge_page_id]['revisions'][0]['*'];

    $data = [];
    $regex = "/\* {{WAM\-art \| title = (.+) \| verdict = ([^|]*) \| last_updated_by = ([^|]*)( \| remarks = (.*) )?}}/";
    preg_match_all($regex, $page_content, $data);

    $ret = [];
    for ($i = 0; $i < count($data[0]); $i++) {
        $ret[$data[1][$i]] = array(
            "verdict" => $data[2][$i],
            "last_updated_by" => $data[3][$i],
            "remarks" => $data[5][$i]
        );
    }

    return $ret;
}

/**
 * Save a judgement to meta-wiki
 * @return void
 */
function do_judgement($verdict, $page_title, $username, $remarks, $wiki = "meta.wikimedia.org") {
    global $settings;

    $ch = null;

    // First fetch the username
    $res = doApiQuery( array(
        'format' => 'json',
        'action' => 'query',
        'meta' => 'userinfo',
    ), $ch );

    if ( isset( $res->error->code ) && $res->error->code === 'mwoauth-invalid-authorization' ) {
        // We're not authorized!
        echo 'You haven\'t authorized this application yet!';
        echo '<hr>';
        return;
    }

    if ( !isset( $res->query->userinfo ) ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Bad API response: <pre>' . htmlspecialchars( var_export( $res, 1 ) ) . '</pre>';
        exit(0);
    }
    if ( isset( $res->query->userinfo->anon ) ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Not logged in. (How did that happen?)';
        exit(0);
    }
    $current_username = $res->query->userinfo->name;

    $edit_page = 'Wikipedia Asian Month/Judging/' . $wiki . "/" . $username;
    $edit_page_id = get_page_id($edit_page);

    $edit_text = "";

    // check whether $edit_page exists
    if ($edit_page_id >= 0) {
        $q = get_judged_articles($username, $wiki);
        // $edit_page exists
        $page_content = $q[1];
        $article_judged = $q[0];

        if (in_array($page_title, $article_judged)) {
            // edit entry {{WAM-art | title = ... | verdict = ... | last_updated_by = ... | remarks = ... }}
            $regex = "/\* {{WAM\-art \| title = " . preg_quote($page_title). " \| verdict = ([^|]*) \| last_updated_by = ([^|]*)( \| remarks = (.*) )?}}/";
            $replacement = '* {{WAM-art'
                . ' | title = '. $page_title
                . ' | verdict = ' . $verdict
                . ' | last_updated_by = ' . $current_username
                . ' | remarks = ' . $remarks . ' }}';

            $edit_text = preg_replace($regex, $replacement, $page_content);

        } else {
            // append entry {{WAM-art | title = ... | verdict = ... | last_updated_by = ... | remarks = ... }}
            $edit_text = $page_content . "\n* {{WAM-art | title = $page_title | verdict = $verdict | last_updated_by = $current_username | remarks = $remarks }}";

        }
    } else {
        // $edit_page does not exist
        // input entry {{WAM-art | title = ... | verdict = ... | last_updated_by = ... | remarks = ... }}
        $edit_text = "* {{WAM-art | title = $page_title | verdict = $verdict | last_updated_by = $current_username | remarks = $remarks }}";

    }

    // Next fetch the edit token
    $res = doApiQuery( array(
        'format' => 'json',
        'action' => 'tokens',
        'type' => 'edit',
    ), $ch );
    if ( !isset( $res->tokens->edittoken ) ) {
        header( "HTTP/1.1 {$settings['errorCode']} Internal Server Error" );
        echo 'Bad API response: <pre>' . htmlspecialchars( var_export( $res, 1 ) ) . '</pre>';
        exit(0);
    }
    $token = $res->tokens->edittoken;

    // Now perform the edit
    $res = doApiQuery( array(
        'format' => 'json',
        'action' => 'edit',
        'title' => $edit_page,
        'text' => $edit_text,
        'summary' => '[[Wikipedia Asian Month|WAM]]: Give ' . $verdict . ' verdict on article ' . $page_title . ' for user ' . $username . ' of '. $wiki,
        'watchlist' => 'nochange',
        'token' => $token,
    ), $ch );

    return $res->edit->result;
}
function get_user_registration_date($username, $wiki = "meta.wikimedia.org") {
    $params = array(
        "action" => "query",
        "list" => "users",
        "format" => "json",
        "usprop" => "registration",
        "ususers" => $username
        );
    return json_decode(api_query($params, $wiki), true)['query']['users'];
}
function get_user_stats($username, $wiki = "meta.wikimedia.org") {
  $judged = get_verdict($username, $wiki);
  $all = get_all_new_pages_of_user($username, $wiki)['query']['usercontribs'];
  $titles_yes = [];

  $appeared = [];

  $cnt = array("yes" => 0, "pending" => 0, "no" => 0, "byte_yes" => 0,
      "reg_date" => 0);

  $cnt['reg_date'] = get_user_registration_date($username, $wiki);
  if (isset($cnt['reg_date'][0]['registration'])) {
    $cnt['reg_date'] = $cnt['reg_date'][0]['registration'];
  } else if (isset($cnt['reg_date'][0]['missing'])) {
    $cnt['reg_date'] = -1;
  } else {
    $cnt['reg_date'] = 0;
  }


  if ($judged) {
    foreach ($judged as $title => $obj) {
      $cnt[$obj['verdict']]++;
      $appeared[$title] = 1;
      if ($obj['verdict'] == 'yes')
        array_push($titles_yes, $title);
    }
  }

  if ($all) {
    foreach ($all as $idx => $obj) {
      if (!isset($appeared[$obj['title']])) {
        $appeared[$obj['title']] = 1;
        $cnt['pending']++;
      }
    }

  }
  $all_page_sizes = get_page_size_using_title($titles_yes, $wiki)['query']['pages'];
  if ($all_page_sizes) {
    foreach ($all_page_sizes as $k => $v) {
      if (isset($v['revisions']))
        $cnt['byte_yes'] += intval($v['revisions'][0]['size']);
    }
  }

  $cnt['all'] = $cnt['yes'] + $cnt['no'] + $cnt['pending'];

  return $cnt;
}
