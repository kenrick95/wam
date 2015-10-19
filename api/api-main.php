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
    $text = get_page_text_content([$pageid], $wiki)['query']['pages'][$pageid]['extract'];
    return str_word_count($text);
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
            "rvprop" => "size",
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
}

function get_meta_page () {
    $url = "http://rest.wikimedia.org:80/meta.wikimedia.org/v1/page/html/Wikipedia_Asian_Month";
    $params = "";
    $data = http_request($url, $params);
    if (empty($data)) {
        throw new Exception("No data received from server. Check that API is enabled.");
    }
    // little processing
    $data = explode("</head>", $data)[1];
    $data = explode("<link>", $data)[0];
    $data = str_replace("./", "https://meta.wikimedia.org/wiki/", $data);

    return $data;
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

    preg_match_all("/{{target\s*\|\s*user\s*=\s*(\S+)\s*\|\s*site\s*=\s*(\S+)\s*}}/", $raw_data, $data);
    //var_dump($data);
    $ret = [];
    for ($i = 1; $i < count($data[2]); $i++) {
        array_push($ret, array(
            "username" => $data[1][$i],
            "wiki" => $data[2][$i]
        ));
    }
    //var_dump($ret);
    //die();
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
    global $gConsumerSecret, $gTokenSecret;

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
    $key = rawurlencode( $gConsumerSecret ) . '&' . rawurlencode( $gTokenSecret );
    return base64_encode( hash_hmac( 'sha1', $toSign, $key, true ) );
}

/**
 * Request authorization
 * @return void
 */
function doAuthorizationRedirect() {
    global $mwOAuthUrl, $mwOAuthAuthorizeUrl, $gUserAgent, $gConsumerKey, $gTokenSecret;

    // First, we need to fetch a request token.
    // The request is signed with an empty token secret and no token key.
    $gTokenSecret = '';
    $url = $mwOAuthUrl . '/initiate';
    $url .= strpos( $url, '?' ) ? '&' : '?';
    $url .= http_build_query( array(
        'format' => 'json',

        // OAuth information
        'oauth_callback' => 'oob', // Must be "oob" for MWOAuth
        'oauth_consumer_key' => $gConsumerKey,
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
    //curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_USERAGENT, $gUserAgent );
    curl_setopt( $ch, CURLOPT_HEADER, 0 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    $data = curl_exec( $ch );
    if ( !$data ) {
        header( "HTTP/1.1 $errorCode Internal Server Error" );
        echo 'Curl error: ' . htmlspecialchars( curl_error( $ch ) );
        exit(0);
    }
    curl_close( $ch );
    $token = json_decode( $data );
    if ( is_object( $token ) && isset( $token->error ) ) {
        header( "HTTP/1.1 $errorCode Internal Server Error" );
        echo 'Error retrieving token: ' . htmlspecialchars( $token->error );
        exit(0);
    }
    if ( !is_object( $token ) || !isset( $token->key ) || !isset( $token->secret ) ) {
        header( "HTTP/1.1 $errorCode Internal Server Error" );
        echo 'Invalid response from token request';
        exit(0);
    }

    // Now we have the request token, we need to save it for later.
    session_start();
    $_SESSION['tokenKey'] = $token->key;
    $_SESSION['tokenSecret'] = $token->secret;
    session_write_close();

    // Then we send the user off to authorize
    $url = $mwOAuthAuthorizeUrl;
    $url .= strpos( $url, '?' ) ? '&' : '?';
    $url .= http_build_query( array(
        'oauth_token' => $token->key,
        'oauth_consumer_key' => $gConsumerKey,
    ) );
    header( "Location: $url" );
    echo 'Please see <a href="' . htmlspecialchars( $url ) . '">' . htmlspecialchars( $url ) . '</a>';
}

/**
 * Handle a callback to fetch the access token
 * @return void
 */
function fetchAccessToken() {
    global $mwOAuthUrl, $gUserAgent, $gConsumerKey, $gTokenKey, $gTokenSecret;

    $url = $mwOAuthUrl . '/token';
    $url .= strpos( $url, '?' ) ? '&' : '?';
    $url .= http_build_query( array(
        'format' => 'json',
        'oauth_verifier' => $_GET['oauth_verifier'],

        // OAuth information
        'oauth_consumer_key' => $gConsumerKey,
        'oauth_token' => $gTokenKey,
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
    //curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_USERAGENT, $gUserAgent );
    curl_setopt( $ch, CURLOPT_HEADER, 0 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    $data = curl_exec( $ch );
    if ( !$data ) {
        header( "HTTP/1.1 $errorCode Internal Server Error" );
        echo 'Curl error: ' . htmlspecialchars( curl_error( $ch ) );
        exit(0);
    }
    curl_close( $ch );
    $token = json_decode( $data );
    if ( is_object( $token ) && isset( $token->error ) ) {
        header( "HTTP/1.1 $errorCode Internal Server Error" );
        echo 'Error retrieving token: ' . htmlspecialchars( $token->error );
        exit(0);
    }
    if ( !is_object( $token ) || !isset( $token->key ) || !isset( $token->secret ) ) {
        header( "HTTP/1.1 $errorCode Internal Server Error" );
        echo 'Invalid response from token request';
        exit(0);
    }

    // Save the access token
    session_start();
    $_SESSION['tokenKey'] = $gTokenKey = $token->key;
    $_SESSION['tokenSecret'] = $gTokenSecret = $token->secret;
    session_write_close();
}


/**
 * Send an API query with OAuth authorization
 *
 * @param array $post Post data
 * @param object $ch Curl handle
 * @return array API results
 */
function doApiQuery( $post, &$ch = null ) {
    global $apiUrl, $gUserAgent, $gConsumerKey, $gTokenKey;

    $headerArr = array(
        // OAuth information
        'oauth_consumer_key' => $gConsumerKey,
        'oauth_token' => $gTokenKey,
        'oauth_version' => '1.0',
        'oauth_nonce' => md5( microtime() . mt_rand() ),
        'oauth_timestamp' => time(),

        // We're using secret key signatures here.
        'oauth_signature_method' => 'HMAC-SHA1',
    );
    $signature = sign_request( 'POST', $apiUrl, $post + $headerArr );
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
    curl_setopt( $ch, CURLOPT_URL, $apiUrl );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $post ) );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( $header ) );
    //curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_USERAGENT, $gUserAgent );
    curl_setopt( $ch, CURLOPT_HEADER, 0 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    $data = curl_exec( $ch );
    if ( !$data ) {
        header( "HTTP/1.1 $errorCode Internal Server Error" );
        echo 'Curl error: ' . htmlspecialchars( curl_error( $ch ) );
        exit(0);
    }
    $ret = json_decode( $data );
    if ( $ret === null ) {
        header( "HTTP/1.1 $errorCode Internal Server Error" );
        echo 'Unparsable API response: <pre>' . htmlspecialchars( $data ) . '</pre>';
        exit(0);
    }
    return $ret;
}

/**
 * Perform a generic edit
 * @return void
 */
function doEdit() {
    global $mwOAuthIW;

    $ch = null;

    // First fetch the username
    $res = doApiQuery( array(
        'format' => 'json',
        'action' => 'query',
        'meta' => 'userinfo',
    ), $ch );

    if ( isset( $res->error->code ) && $res->error->code === 'mwoauth-invalid-authorization' ) {
        // We're not authorized!
        echo 'You haven\'t authorized this application yet! Go <a href="' . htmlspecialchars( $_SERVER['SCRIPT_NAME'] ) . '?action=authorize">here</a> to do that.';
        echo '<hr>';
        return;
    }

    if ( !isset( $res->query->userinfo ) ) {
        header( "HTTP/1.1 $errorCode Internal Server Error" );
        echo 'Bad API response: <pre>' . htmlspecialchars( var_export( $res, 1 ) ) . '</pre>';
        exit(0);
    }
    if ( isset( $res->query->userinfo->anon ) ) {
        header( "HTTP/1.1 $errorCode Internal Server Error" );
        echo 'Not logged in. (How did that happen?)';
        exit(0);
    }
    $page = 'User talk:' . $res->query->userinfo->name;

    // Next fetch the edit token
    $res = doApiQuery( array(
        'format' => 'json',
        'action' => 'tokens',
        'type' => 'edit',
    ), $ch );
    if ( !isset( $res->tokens->edittoken ) ) {
        header( "HTTP/1.1 $errorCode Internal Server Error" );
        echo 'Bad API response: <pre>' . htmlspecialchars( var_export( $res, 1 ) ) . '</pre>';
        exit(0);
    }
    $token = $res->tokens->edittoken;

    // Now perform the edit
    $res = doApiQuery( array(
        'format' => 'json',
        'action' => 'edit',
        'title' => $page,
        'section' => 'new',
        'sectiontitle' => 'Hello, world',
        'text' => 'This message was posted using the OAuth Hello World application, and should be seen as coming from yourself. To revoke this application\'s access to your account, visit [[:' . $mwOAuthIW . ':Special:OAuthManageMyGrants]]. ~~~~',
        'summary' => '/* Hello, world */ Hello from OAuth!',
        'watchlist' => 'nochange',
        'token' => $token,
    ), $ch );

    echo 'API edit result: <pre>' . htmlspecialchars( var_export( $res, 1 ) ) . '</pre>';
    echo '<hr>';
}
